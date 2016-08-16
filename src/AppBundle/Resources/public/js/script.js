jQuery('.date-picker').datetimepicker({
    format: 'Y年m月d日'
});

jQuery('.time-picker').datetimepicker({
    format: 'H時i分'
});

var map = new GMaps({
    el: '#map',
    lat: -12.043333,
    lng: -77.028333
});
