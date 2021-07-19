const filter_panel = document.getElementById("filter_panel");
const sort_panel = document.getElementById("sort_panel");
const img_show = document.getElementById("img_show");

sort_panel.style.display　= "none";
filter_panel.style.display　= "none";

function sort_show() {
    if(sort_panel.style.display == "none"){
        sort_panel.style.display = "block";
        filter_panel.style.display =　"none";
        img_show.style.display =　"none";
    }else{
        sort_panel.style.display = "none";
        img_show.style.display =　"block";
    }
}

function filter_show() {
    if(filter_panel.style.display == "none"){
        filter_panel.style.display = "block";
        sort_panel.style.display =　"none";
        img_show.style.display =　"none";
    }else{
        filter_panel.style.display = "none";
        img_show.style.display =　"block";
    }
}

function sort_cancel() {
    console.log("発火");
    img_show.style.display =　"block";
    sort_panel.style.display　= "none";
}

function filter_cancel() {
    console.log("発火");
    img_show.style.display =　"block";
    filter_panel.style.display　= "none";
}
function filter_reset(){
    var url = "search_img.php";
    location.href= url;
}

function sort_reset(){
    var sort_date = document.getElementById("sort_date");
    sort_date.options[0].selected = true;
    filter_and_sort_go();
}

function filter_and_sort_go() {
    genre_id = document.getElementById("genre").value;
    prefecture_id = document.getElementById("prefecture").value;
    key_word = document.getElementById("keyword").value;

    sort_date = document.getElementById("sort_date").value;

    var parameter = false;

    var url = "search_img.php";

    if(genre_id != "0") {
        url = parameter_add(parameter,url,"g",genre_id);
        parameter = true;
    }
    if(prefecture_id != "0"){
        url = parameter_add(parameter,url,"p",prefecture_id);
        parameter = true;
    }
    if(key_word != ""){
        let s_list = ["/",　"?", "#",　"[",　"]",　"@",　"!",　"$",　"&",　"'",　"(",　")", "*",　"+",　",",　";",　"=", "%", " " , "　"];
        for (const element of s_list) {
            key_word = key_word.replace(element , "+");
            //console.log(key_word);
        }
        url = parameter_add(parameter,url,"k",key_word);
        parameter = true;
    }
    if(sort_date != "0"){
        url = parameter_add(parameter,url,"sd",sort_date);
    }

    location.href= url;
}

function parameter_add(parameter,url,key,value){
    if(!parameter){
        url += "?";
    }else{
        url += "&";
    }
    url = url + key + "=" + value;
    return url;
}
