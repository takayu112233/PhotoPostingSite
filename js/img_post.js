
const max_size = 1080;

sys_msg = document.getElementById("system_msg");

var canvas = document.getElementById("img_canvas");
var ctx = canvas.getContext('2d');
var canvas_w = canvas.width;
var canvas_h = canvas.height;
ctx.fillStyle = 'gray';
ctx.fillRect(0, 0, canvas_w, canvas_h);

sys_msg.innerText = "写真を選択してください";

var img;

var filename;
var comment;
var shop_name;
var shop_genre;
var shop_id;
var type;

var post_ready = false;


$('input[type=file]').change(function(){
    console.log("処理開始");
    img = this.files[0];

    if(img == null){
        document.getElementById("preview_img").innerHTML = "";
        sys_msg.innerText = "写真を選択してください";
        return;
    }

    if (img.type != 'image/jpeg' && img.type != 'image/png') {
        document.getElementById("preview_img").innerHTML = "";
        document.getElementById("image").value = "";
        sys_msg.innerText = "写真を選択してください";
        return;
    }

    var image = new Image();
    var reader = new FileReader();

    reader.onload = function(e) {
        image.onload = function() {
            post_ready = false;

            sys_msg.innerText = "写真をサーバに送信しています";
    
            if(image.width > image.height){
                var ritu = image.height/image.width;
                width = max_size;
                height = max_size * ritu;
            } else {
                var ritu = image.width/image.height;
                width = max_size * ritu;
                height = max_size;
            }

            var canvas = $('#img_canvas').attr('width', width).attr('height', height);

            ctx.clearRect(0,0,width,height);

            ctx.drawImage(image,
                0, 0, image.width, image.height,
                0, 0, width, height
            );
            
            var canvas = document.getElementById("img_canvas");
            var base64 = canvas.toDataURL('image/jpeg');

            //console.log(base64);

            var fd = new FormData();

            //console.log(base64);

            $.ajax({
                url: "./img_save.php",
                type:'POST',
                dataType: 'html',
                data : {image : base64},
                timeout:3000,
            })
            .done(function( data, textStatus, jqXHR ){
                document.getElementById("preview_img").innerHTML = "<img src=\"./upload_img/kari_box/" + data + "\" alt=\"プレビュー\" title=\"プレビュー\" style=\"width: 200px;\">";
                filename = data;
                sys_msg.innerText = "";

                post_ready = true;
            })
            .fail(function( jqXHR, textStatus, errorThrown ){
                post_ready = false;
                console.log("失敗");
                document.getElementById("image").value = "";
                sys_msg.innerText = "画像を送信できませんでした";
                
                var canvas = $('#img_canvas').attr('width', 400).attr('height', 300);
                ctx.fillStyle = 'gray';
                ctx.fillRect(0, 0, canvas_w, canvas_h);
            });
        }
        image.src = e.target.result;
    }
    reader.readAsDataURL(img);
});

function commit(){
    if(!post_ready){
        alert("写真をアップロード中です");
        return;
    }
    if(img == null){
        alert("写真がアップロードされていません");
        return;
    }else{
        comment = document.getElementById("comment").value;
        post("./commit.php", {filename:filename,comment:comment,shop_name:shop_name,shop_genre:shop_genre,shop_id:shop_id,type:type});
    }
}

function post(path, params, method='post') {
    const form = document.createElement('form');
    form.method = method;
    form.action = path;

    for (const key in params) {
        if (params.hasOwnProperty(key)) {
            const hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = key;
            hiddenField.value = params[key];

            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
}