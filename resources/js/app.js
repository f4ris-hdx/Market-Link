<script>
document.addEventListener('DOMContentLoaded', function () {
    const marketSelect = document.getElementById('regMarket');
    const locationPreview = document.getElementById('marketLocationPreview');

    function updateMarketLocation() {
        const option = marketSelect.options[marketSelect.selectedIndex];

        if (option && option.dataset.location) {
            locationPreview.textContent = 'Location: ' + option.dataset.location;
        } else {
            locationPreview.textContent = 'Select a market to see its location.';
        }
    }

    marketSelect.addEventListener('change', updateMarketLocation);
    updateMarketLocation();
});
</script>