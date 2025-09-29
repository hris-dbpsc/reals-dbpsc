
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @foreach($timeOffs as $timeOff)
        const fromInput{{ $timeOff->id }} = document.querySelector('#editLeaveModal{{ $timeOff->id }} input[name="leave_date_from"]');
        const toInput{{ $timeOff->id }} = document.querySelector('#editLeaveModal{{ $timeOff->id }} input[name="leave_date_to"]');
        const daysInput{{ $timeOff->id }} = document.querySelector('#editLeaveModal{{ $timeOff->id }} input[name="number_of_days"]');

        function updateDays{{ $timeOff->id }}() {
            const from = new Date(fromInput{{ $timeOff->id }}.value);
            const to = new Date(toInput{{ $timeOff->id }}.value);
            if (from && to && !isNaN(from) && !isNaN(to) && to >= from) {
                // Simple calculation: inclusive days
                const diff = Math.floor((to - from) / (1000 * 60 * 60 * 24)) + 1;
                daysInput{{ $timeOff->id }}.value = diff;
            } else {
                daysInput{{ $timeOff->id }}.value = '';
            }
        }
        fromInput{{ $timeOff->id }}.addEventListener('change', updateDays{{ $timeOff->id }});
        toInput{{ $timeOff->id }}.addEventListener('change', updateDays{{ $timeOff->id }});
        @endforeach
    });
</script>