<script>
document.addEventListener('DOMContentLoaded', function() {
    const fromInput = document.getElementById('leave_date_from');
    const toInput = document.getElementById('leave_date_to');
    const daysInput = document.getElementById('number_of_days');
    // Holidays from backend
    const holidays = {!! json_encode($holidaysArr) !!};
    function countWorkingDays(from, to) {
        if (!from || !to) return 0;
        let start = new Date(from);
        let end = new Date(to);
        let count = 0;
        for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
            const day = d.getDay();
            const dateStr = d.toISOString().slice(0,10);
            // 0 = Sunday, 6 = Saturday
            if (day !== 0 && day !== 6 && !holidays.includes(dateStr)) {
                count++;
            }
        }
        return count;
    }
    function updateDays() {
        const from = fromInput.value;
        const to = toInput.value;
        if (from && to && from <= to) {
            daysInput.value = countWorkingDays(from, to);
        } else {
            daysInput.value = '';
        }
    }
    if (fromInput && toInput && daysInput) {
        fromInput.addEventListener('change', updateDays);
        toInput.addEventListener('change', updateDays);
    }
});
</script>