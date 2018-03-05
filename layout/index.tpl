<script>
window.addEventListener('load', function() {
    document.getElementById('button').addEventListener('click', function() {
        var xmlHttp = new XMLHttpRequest();
        var formData = new FormData();
        formData.append('url', document.getElementById('url').value);
        xmlHttp.onreadystatechange = function()
        {
            if(xmlHttp.readyState == 4 && xmlHttp.status == 200)
            {
                var obj = JSON.parse(xmlHttp.responseText);
                if (obj.status == 200) {
                    $('#url').val(obj.short);
                    $('#icon').removeClass("icon-link");
                    $('#icon').removeClass("icon-warning-sign");
                    $('#icon').addClass("icon-ok");
                    $('#p').removeClass("has-error");
                    $('#p').addClass("has-success");
                    $("#text").html('<center><a href="'+obj.short+'+" target="_blank">Klickstatistiken</a></center>');
                } else {
                    $('#icon').removeClass("icon-link");
                    $('#icon').removeClass("icon-ok");
                    $('#icon').addClass("icon-warning-sign");
                    $('#p').removeClass("has-success");
                    $('#p').addClass("has-error");
                    $("#text").html('<center>'+obj.message+'</center>');
                }
            }
        }
        xmlHttp.open("post", "api.php");
        xmlHttp.send(formData);
    });
});
</script>

<div class="container">
    <div class="row">

        <div class="col-lg-12 well-lg" style="margin-top: 5%;padding: 5px;">
            <center><img src="images/logo.png" alt="Logo {lng_sitename}" /></center><br />

            <div class="input-group mb-2" id="p">
                <div class="input-group-prepend">
                    <div class="input-group-text"><span id="icon" class="icon-link"></span></div>
                </div>
                <input type="text" class="form-control input-lg " name="url" id="url" placeholder="google.com" /><br />
            </div>
            <p class="text-center">
                <div id="text"></div>
            </p>

            <p class="text-center">
                <button type="submit" id="button"  class="btn btn-success btn-lg">{lng_btn_short}</button><br />
                <small style="font-size:small;"><a href="rules.html" target="_blank"><center>{lng_accept_rules}</center></a></small>
            </p>
        </div>
    </div>
</div>