<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Category;
use App\Models\Farmer;
use App\Models\Market;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $metrics = [
            'users' => User::count(),
            'customers' => User::where('role', 'customer')->count(),
            'farmers' => User::where('role', 'farmer')->count(),
            'pendingFarmers' => Farmer::where('status', 'pending')->count(),
            'verifiedFarmers' => Farmer::where('status', 'verified')->count(),
            'products' => Product::count(),
            'orders' => Order::count(),
            'revenue' => (float) Order::whereNotIn('status', ['Cancelled', 'Rejected'])->sum('total'),
            'markets' => Market::count(),
            'reviews' => Review::where('status', 'published')->count(),
            'averageRating' => (float) (Review::where('status', 'published')->avg('rating') ?? 0),
        ];
        $announcements = Announcement::orderByDesc('created_at')->get();
        $recentOrders = Order::with('user')->orderByDesc('placed_at')->limit(10)->get();
        $recentReviews = Review::with(['user', 'product', 'farmer'])
            ->where('status', 'published')
            ->latest()
            ->limit(12)
            ->get();

        return view('admin.dashboard', compact('metrics', 'announcements', 'recentOrders', 'recentReviews'));
    }

    // -------------------- Users CRUD --------------------
    public function users(Request $request): View
    {
        $query = User::query()->orderByDesc('created_at');
        if ($request->filled('role')) {
            $query->where('role', $request->string('role')->toString());
        }
        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }
        if ($request->filled('search')) {
            $term = $request->string('search')->toString();
            $query->where(fn ($q) => $q->where('name', 'like', "%{$term}%")->orWhere('email', 'like', "%{$term}%"));
        }
        $users = $query->paginate(20)->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function createUser(): View
    {
        return view('admin.user-form');
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'role' => ['required', 'in:customer,farmer,admin'],
            'status' => ['required', 'in:active,inactive'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        if ($user->isFarmer()) {
            Farmer::create(['user_id' => $user->id, 'name' => $user->name, 'owner_name' => $user->name, 'location' => 'Pending confirmation', 'status' => 'pending']);
        }

        return redirect()->route('admin.users')->with('status', 'User created.');
    }

    public function editUser(User $user): View
    {
        return view('admin.user-form', compact('user'));
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone' => ['nullable', 'string', 'max:30'],
            'role' => ['required', 'in:customer,farmer,admin'],
            'status' => ['required', 'in:active,inactive'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);
        if ($user->is(auth()->user()) && ($data['status'] === 'inactive' || $data['role'] !== 'admin')) {
            return back()->withErrors(['user' => 'You cannot deactivate or demote your own administrator account.'])->withInput();
        }
        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }
        $oldRole = $user->role;
        $user->update($data);

        if ($oldRole !== 'farmer' && $user->isFarmer()) {
            Farmer::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $user->name,
                    'owner_name' => $user->name,
                    'location' => 'Pending confirmation',
                    'status' => 'pending',
                    'is_demo' => false,
                ],
            );
        } elseif ($oldRole === 'farmer' && ! $user->isFarmer()) {
            $user->farmer()?->update(['status' => 'suspended']);
        }

        return redirect()->route('admin.users')->with('status', 'User updated.');
    }

    public function toggleUser(User $user): RedirectResponse
    {
        abort_if($user->is(auth()->user()), 403, 'You cannot disable your own account.');

        $currentlyActive = ($user->status ?? 'active') === 'active';
        if ($user->isAdmin() && $currentlyActive) {
            $activeAdmins = User::where('role', 'admin')->where('status', 'active')->count();
            abort_if($activeAdmins <= 1, 403, 'The last active administrator account cannot be disabled.');
        }

        $user->update(['status' => $currentlyActive ? 'inactive' : 'active']);

        return back()->with('status', "{$user->name} is now {$user->status}.");
    }

    public function destroyUser(User $user): RedirectResponse
    {
        abort_if($user->is(auth()->user()), 403, 'You cannot delete your own account.');

        if ($user->isAdmin()) {
            $adminCount = User::where('role', 'admin')->count();
            abort_if($adminCount <= 1, 403, 'The last administrator account cannot be deleted.');
        }

        // A farmer user's profile owns the farmer's products, so remove the profile
        // first instead of leaving a broken farmer-role login behind.
        $user->farmer?->delete();
        $user->delete();

        return back()->with('status', 'User and related farmer profile (when present) deleted.');
    }

    // -------------------- Farmers CRUD --------------------
    public function farmers(Request $request): View
    {
        $query = Farmer::with(['user', 'market'])->withCount('products')->orderByDesc('created_at');
        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        $type = $request->string('type')->toString();
        if ($type === 'registered') {
            $query->where('is_demo', false);
        }
        if ($type === 'sample') {
            $query->where('is_demo', true);
        }

        $farmers = $query->get();

        return view('admin.farmers', compact('farmers', 'type'));
    }

    public function createFarmer(): View
    {
        $users = User::where('role', 'farmer')->orderBy('name')->get();
        $markets = Market::orderBy('name')->get();

        return view('admin.farmer-form', compact('users', 'markets'));
    }

    public function storeFarmer(Request $request): RedirectResponse
    {
        $data = $this->validateFarmer($request);
        if (! empty($data['user_id'])) {
            $data['is_demo'] = false;
        } else {
            $data['is_demo'] = true;
        }
        $farmer = Farmer::create($data);
        $farmer->user?->update(['status' => $farmer->status === 'suspended' ? 'inactive' : 'active']);

        return redirect()->route('admin.farmers')->with('status', 'Farmer created.');
    }

    public function editFarmer(Farmer $farmer): View
    {
        $users = User::where('role', 'farmer')->orderBy('name')->get();
        $markets = Market::orderBy('name')->get();

        return view('admin.farmer-form', compact('farmer', 'users', 'markets'));
    }

    public function updateFarmer(Request $request, Farmer $farmer): RedirectResponse
    {
        $data = $this->validateFarmer($request, $farmer);
        $previousUser = $farmer->user;
        $data['is_demo'] = empty($data['user_id']);
        $farmer->update($data);

        if ($previousUser && (int) $previousUser->id !== (int) ($farmer->user_id ?? 0) && $previousUser->role === 'farmer') {
            $previousUser->update(['role' => 'customer', 'status' => 'active']);
        }

        $farmer->user?->update(['status' => $farmer->status === 'suspended' ? 'inactive' : 'active']);

        return redirect()->route('admin.farmers')->with('status', 'Farmer updated.');
    }

    private function validateFarmer(Request $request, ?Farmer $farmer = null): array
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'specialty' => ['nullable', 'string', 'max:255'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'image' => ['nullable', 'url', 'max:2048'],
            'market_id' => ['nullable', 'exists:markets,id'],
            'status' => ['required', 'in:pending,verified,suspended'],
            'slots' => ['nullable', 'string', 'max:1000'],
        ]);
        if (! empty($data['user_id'])) {
            $exists = Farmer::where('user_id', $data['user_id'])->when($farmer, fn ($q) => $q->where('id', '!=', $farmer->id))->exists();
            abort_if($exists, 422, 'That farmer user already has a farmer profile.');
            abort_unless(User::whereKey($data['user_id'])->where('role', 'farmer')->exists(), 422, 'Selected user must have the farmer role.');
        }

        return $data;
    }

    public function verify(Farmer $farmer): RedirectResponse
    {
        $farmer->update(['status' => 'verified']);
        $farmer->user?->update(['status' => 'active']);

        return back()->with('status', "{$farmer->name} verified.");
    }

    public function suspend(Farmer $farmer): RedirectResponse
    {
        $farmer->update(['status' => 'suspended']);
        $farmer->user?->update(['status' => 'inactive']);

        return back()->with('status', "{$farmer->name} suspended.");
    }

    public function deleteFarmer(Farmer $farmer): RedirectResponse
    {
        $farmer->user?->update(['role' => 'customer', 'status' => 'active']);
        $farmer->delete();

        return back()->with('status', 'Farmer profile deleted and any linked account was returned to customer access.');
    }

    // -------------------- Products CRUD --------------------
    public function products(Request $request): View
    {
        $query = Product::query()->with(['farmer', 'category']);
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->string('search')->toString().'%');
        }
        $products = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('admin.products', compact('products'));
    }

    public function createProduct(): View
    {
        $farmers = Farmer::where('status', 'verified')->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $markets = Market::orderBy('name')->get();

        return view('admin.product-form', compact('farmers', 'categories', 'markets'));
    }

    public function storeProduct(Request $request): RedirectResponse
    {
        $data = $this->validateProduct($request);
        $marketId = $data['market_id'];
        unset($data['market_id']);

        $product = Product::create($data);
        $product->markets()->sync([$marketId]);

        return redirect()->route('admin.products')->with('status', 'Product created.');
    }

    public function editProduct(Product $product): View
    {
        $farmers = Farmer::where('status', 'verified')->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $markets = Market::orderBy('name')->get();
        $product->load('markets');
        $selectedMarketId = $product->markets->first()?->id;

        return view('admin.product-form', compact('product', 'farmers', 'categories', 'markets', 'selectedMarketId'));
    }

    public function updateProduct(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validateProduct($request);
        $marketId = $data['market_id'];
        unset($data['market_id']);

        $product->update($data);
        $product->markets()->sync([$marketId]);

        return redirect()->route('admin.products')->with('status', 'Product updated.');
    }

    private function validateProduct(Request $request): array
    {
        $data = $request->validate([
            'farmer_id' => ['required', 'exists:farmers,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'category_id' => ['required', 'exists:categories,id'],
            'market_id' => ['required', 'integer', 'exists:markets,id'],
            'unit' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'image' => ['nullable', 'url', 'max:2048'],
        ]);

        abort_unless(Farmer::whereKey($data['farmer_id'])->where('status', 'verified')->exists(), 422, 'Products can only be assigned to verified farmers.');

        return $data;
    }

    public function destroyProduct(Product $product): RedirectResponse
    {
        $product->delete();

        return back()->with('status', 'Product deleted.');
    }

    // -------------------- Markets & announcements --------------------
    public function markets(): View
    {
        $markets = Market::withCount('farmers')->orderBy('name')->get();

        return view('admin.markets', compact('markets'));
    }

    public function storeMarket(Request $request): RedirectResponse
    {
        Market::create($this->validateMarket($request));

        return back()->with('status', 'Market created.');
    }

    public function updateMarket(Request $request, Market $market): RedirectResponse
    {
        $market->update($this->validateMarket($request));

        return back()->with('status', 'Market updated.');
    }

    private function validateMarket(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'days' => ['required', 'string', 'max:255'],
            'farmers_count' => ['nullable', 'integer', 'min:0'],
            'distance' => ['nullable', 'numeric', 'min:0'],
        ]);

        $data['farmers_count'] = $data['farmers_count'] ?? 0;
        $data['distance'] = $data['distance'] ?? 0;

        return $data;
    }

    public function destroyMarket(Market $market): RedirectResponse
    {
        $market->delete();

        return back()->with('status', 'Market removed.');
    }

    public function storeAnnouncement(Request $request): RedirectResponse
    {
        $data = $request->validate(['title' => ['required', 'string', 'max:255'], 'message' => ['required', 'string', 'max:1000']]);
        Announcement::create($data);

        return redirect()->route('admin.dashboard')->with('status', 'Announcement published.');
    }

    public function destroyAnnouncement(Announcement $announcement): RedirectResponse
    {
        $announcement->delete();

        return back()->with('status', 'Announcement removed.');
    }
}
