Menu = (function() {

    var self = this;
    self.id_prefix = '#m-';
    self.root = '#menu';

    function setUpperActive(element)
    {
        var upper = $(element).parent('ul').parent('li');

        if (upper.index() >= 0) {
            upper.addClass('active');
            setUpperActive(upper);
        }
    }

    function removeActives()
    {
        $(self.root + ' li').removeClass('active');
    }

    return {
        setActive: function(name)
        {
            var element = $(self.id_prefix + name);
            if (element) {
                removeActives();

                var parent = $(element).parent('li');
                parent.addClass('active');

                setUpperActive(parent);
            }
        }
    };
}());
