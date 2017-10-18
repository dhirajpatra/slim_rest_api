// API call from front
var API = {
    call:function(url,callback,data){
        var data = (!!data) ? data : {};
        var callback = (!!callback) ? callback : function(){};

        $.ajax({
            type: "POST",
            dataType: "jsonp",
            crossDomain: true,
            cache: false,
            xhrFields: { withCredentials: true },
            url: "/api" + url,
            data: data,
            success: callback,
            error: function(data){
                console.log(data);
            }
        });
    }
}

