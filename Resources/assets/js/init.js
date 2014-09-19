$(function() {
    Highcharts.setOptions({
        colors: [
            '#3ac0ff',
            '#6ee877',
            '#ffd025',
            '#ff7889',
            '#bf82e8',
            '#94b6aa',
            '#d1bc8e',
            '#6683a8',
            '#1e64ba',
            '#134e74'
        ]
    });

    $('.decoda-chart').each(function() {
        $(this).highcharts(
            $(this).data('chart')
        );
    });
});
