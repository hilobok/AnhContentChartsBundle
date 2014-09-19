;(function() {
    var options = $.editor();

    var tags = (options.tags || []).concat([
        // chart filter
        'chart'
    ]);

    $.editor({ tags: tags });
})();