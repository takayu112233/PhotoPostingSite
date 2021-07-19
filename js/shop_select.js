let prefecture_arr = ['北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県', '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県', '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県', '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府', '兵庫県', '奈良県', '和歌山県', '鳥取県', '島根県', '岡山県', '広島県', '山口県', '徳島県', '香川県', '愛媛県', '高知県', '福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県'];
var select_shop_no = 0

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


function shop_select(select_shop_id){
    for(var i=0 ; i<shop_data.length ; i++){
        if(shop_data[i].shop_id == select_shop_id){
            var shop_id = shop_data[i].shop_id; 
            var shop_name = shop_data[i].shop_name; 
            var genre = shop_data[i].gerne_name; 

            post("img_post.php", {shop_id:shop_id,shop_name:shop_name,genre:genre});
        }
    }
}

function hyphen_delete(){
    var postcode = document.getElementById("postcode");
    postcode.value = (postcode.value).replace(/[^0-9]/g, '');
}

function manual_insert(){
    document.getElementById("make_shop").style.display = "flex";
    document.getElementById("shop_add_auto").style.display = "none";
    document.getElementById("shop_add_manual").style.display = "block";
    document.getElementById("system_msg_manual").innerHTML = "施設情報を入力して登録を押してください。";
    document.getElementById("system_msg").innerHTML = "";
    document.getElementById('search_result').innerHTML = "";
}

function auto_insert(){
    document.getElementById("make_shop").style.display = "flex";
    document.getElementById("shop_add_manual").style.display = "none";
    document.getElementById("shop_add_auto").style.display = "block";
    document.getElementById("system_msg").innerHTML = "";
    document.getElementById('search_result').innerHTML = "";
}

function shop_search(){
    document.getElementById("search_result").innerHTML = "検索中";
    var shop_name = document.getElementById("shopname_search").value;
    var url = "./api/get_google_place.php?s_name=" + shop_name; 
    $.ajax({
        url: url, 
        dataType: 'html', 
        success: function (data) {
            map_arr = JSON.parse(data)
            //console.log(map_arr["results"]);

            document.getElementById('search_result').innerHTML = "";
            html = "";
            cnt = 0;
            for(var no in map_arr["results"]){
                //console.log(map_arr["results"][no])
                shop_name = map_arr["results"][no]["name"];
                shop_address_and_postcode = map_arr["results"][no]["formatted_address"];
                place_id = map_arr["results"][no]["place_id"];

                post_code_w = shop_address_and_postcode.indexOf("〒", 0);
                if(post_code_w == -1) continue;

                shop_postcode = shop_address_and_postcode.substr(post_code_w + 1, 8);
                shop_postcode =　shop_postcode.replace("-", "");

                address_w = shop_address_and_postcode.indexOf(" ", post_code_w + 1);
                shop_address_and_prefecture = shop_address_and_postcode.substr(address_w + 1);
                shop_address_and_prefecture = shop_address_and_prefecture.replace(/^(.{2}[都道府県]|.{3}県)(.+)/, "$1 $2");

                address_w = shop_address_and_prefecture.indexOf(" ", 0);
                address = shop_address_and_prefecture.substr(address_w);

                prefecture = shop_address_and_prefecture.substr(0,address_w);

                cnt++;
                if(cnt ==1){
                    html = html + "<p>検索結果から施設を選択してください</p>";
                    html = html + "<table class=\"shop_t\" id=\"google_place_t\">";
                    html = html + "<tbody>";
                    html = html + "<tr>";
                    html = html + "<th><a>名称</a></th>";
                    html = html + "<th><a>郵便番号</a></th>";
                    html = html + "<th><a>都道府県</a></th>";
                    html = html + "<th><a>住所</a></th>";
                    html = html + "<th><a>p_id</a></th>";
                    html = html + "</tr>";
                }


                html = html +　"<tr>";
                html = html +　"<td>" + shop_name + "</td>";
                html = html +　"<td>" + shop_postcode + "</td>";
                html = html +　"<td>" + prefecture + "</td>";
                html = html +　"<td>" + address +  "</td>";
                html = html +　"<td>" + place_id +  "</td>";
                html = html +　"<td id=\"map_button\"><a href=\"javascript:void(0)\" class=\"button\" id=\"table_button\" onClick=\"shop_select_g(" + cnt + ");return false;\">選択</a></td>\n";

                html = html +　"</tr>";

                //html = html + no + ":</br>";
                //html = html + "元データ:\t" + shop_name + " " +shop_address_and_postcode + "</br>";
                //html = html + shop_address_and_postcode + ":</br>";
                //html = html + shop_address_and_prefecture + "</br>";
                //html = html + "郵便番号:\t" + shop_postcode + "</br>";
                //html = html + "都道府県:\t" + prefecture + "</br>";
                //html = html + "施設名称:\t" + shop_name +  "</br>";
                //html = html + "住所　　:\t" + address + "</br>";
                //html = html + "</br>";
            }
            //html = html + "検索個数: " + cnt + "</br>";
            if(cnt == 0){
                html = html + "<p>検索結果がありませんでした。</p>手動登録してください<br>";
            }else{
                html = html + "</tbody>";
                html = html + "</table>";
            }
            document.getElementById('search_result').innerHTML = html;
        },
        error: function () {
            document.getElementById("search_result").innerHTML = "エラーが発生しました。<br>手動登録してください<br>";
        },
    });
}

function shop_db_show(){
    document.getElementById("shop_db_show_button").style.display = "none";
    document.getElementById("shop_t").style.display = "block";
}

function shop_select_g(cnt){
    select_shop_no = cnt;
    console.log(select_shop_no);

    document.getElementById("select_genre").style.display = "block";
    document.getElementById("shop_select").style.display = "none";
    document.getElementById("shop_add").style.display = "none";

    var google_place_t = document.getElementById("google_place_t");

    var url = "./api/exists_db_place_id.php?place_id=" + google_place_t.rows[cnt].cells[4].innerHTML; 


    console.log(url)
    $.ajax({
        url: url, 
        dataType: 'html', 
        success: function (data) {
            console.log(JSON.parse(data))
            result = JSON.parse(data);
            if(result["exisis"] == false){
                document.getElementById("select_genre_msg").innerHTML = google_place_t.rows[cnt].cells[0].innerHTML + " のジャンルを選択してください"
            }
            else{
                shop_name = result["shop_name"];
                shop_id = result["shop_id"];
                genre = result["gerne_name"];
                
                post("img_post.php", {shop_id:shop_id,shop_name:shop_name,genre:genre});
            }
        },
        error: function () {
            document.getElementById("search_result").innerHTML = "エラーが発生しました。<br>手動登録をしてください。<br>";
        },
    });
}

function shop_add_google(){
    var google_place_t = document.getElementById("google_place_t");
    
    var genre = document.getElementById("genre_google").value;
    var shopname = google_place_t.rows[select_shop_no].cells[0].innerHTML; 
    var postcode = google_place_t.rows[select_shop_no].cells[1].innerHTML;
    var address = google_place_t.rows[select_shop_no].cells[3].innerHTML;
    var place_id = google_place_t.rows[select_shop_no].cells[4].innerHTML;

    var prefecture_name = google_place_t.rows[select_shop_no].cells[2].innerHTML;
    var genre_name = document.getElementById("genre_google").options[genre-1].text;

    var prefecture = prefecture_arr.indexOf(prefecture_name) + 1;


    console.log(select_shop_no);
    console.log(genre);
    console.log(prefecture);
    console.log(shopname);
    console.log(postcode);
    console.log(address);

    post("", {button:"追加_g","genre":genre,"prefecture":prefecture,"shopname":shopname,"postcode":postcode,"address":address,"place_id":place_id,"prefecture_name":prefecture_name,"genre_name":genre_name})
}


function shop_add(){
    document.getElementById("system_msg").innerHTML = "実行";

    var genre = document.getElementById("genre").value; 
    var prefecture = document.getElementById("prefecture").value; 
    var shopname = document.getElementById("shopname").value; 
    var postcode = document.getElementById("postcode").value; 
    var address = document.getElementById("address").value; 

    post("", {button:"追加","genre":genre,"prefecture":prefecture,"shopname":shopname,"postcode":postcode,"address":address})
}

function on_keyboard(code)
{
	if(13 === code)
	{
        shop_search();
	}
}
