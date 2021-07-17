CREATE DATABASE g15 CHARACTER SET utf8 COLLATE utf8_general_ci;
USE g15;
CREATE USER 'g15'@'localhost';
GRANT ALL ON *.* to 'g15'@'localhost';
SET PASSWORD FOR 'g15'@'localhost' = PASSWORD('g15');

create table users(
user_id int auto_increment,
user_name varchar(30) unique,
pass varchar(64) not null,
delete_flag int default 0,
nickname varchar(100) not null,
primary key (user_id)
);

create table prefectures(
prefecture_id int ,
prefecture_name varchar(10) not null,
primary key (prefecture_id)
);

create table gerne(
gerne_id int auto_increment,
gerne_name varchar(30) not null,
primary key (gerne_id)
);

create table shop(
shop_id int auto_increment,
gerne_id int not null,
prefecture_id int not null,
shop_name varchar(100),
postcode int,
address varchar(60),
latitude double(8,6),
longitude double(8,6),
delete_flag int default 0,
closed int default 0,
place_id varchar(60) unique,
primary key (shop_id),
foreign key(gerne_id) references gerne(gerne_id),
foreign key(prefecture_id) references prefectures(prefecture_id)
);

create table photo_data(
photo_id int auto_increment,
user_id int not null,
shop_id int not null,
photo_url varchar(255) not null,
comment text not null,
delete_flag int default 0,
primary key (photo_id),
foreign key (shop_id) references shop(shop_id), 
foreign key(user_id) references users(user_id)
);

create table bookmark(
bookmark_id int auto_increment,
user_id int not null,
photo_id int not null,
primary key (bookmark_id),
foreign key (user_id) references users(user_id),
foreign key (photo_id) references photo_data(photo_id)
);

INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (1,"北海道");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (2,"青森県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (3,"岩手県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (4,"宮城県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (5,"秋田県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (6,"山形県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (7,"福島県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (8,"茨城県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (9,"栃木県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (10,"群馬県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (11,"埼玉県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (12,"千葉県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (13,"東京都");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (14,"神奈川県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (15,"新潟県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (16,"富山県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (17,"石川県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (18,"福井県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (19,"山梨県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (20,"長野県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (21,"岐阜県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (22,"静岡県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (23,"愛知県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (24,"三重県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (25,"滋賀県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (26,"京都府");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (27,"大阪府");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (28,"兵庫県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (29,"奈良県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (30,"和歌山県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (31,"鳥取県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (32,"島根県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (33,"岡山県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (34,"広島県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (35,"山口県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (36,"徳島県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (37,"香川県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (38,"愛媛県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (39,"高知県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (40,"福岡県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (41,"佐賀県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (42,"長崎県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (43,"熊本県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (44,"大分県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (45,"宮崎県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (46,"鹿児島県");
INSERT INTO prefectures(prefecture_id,prefecture_name) VALUES (47,"沖縄県");

INSERT INTO gerne(gerne_name) VALUES ("グルメ"),("カフェ"),("レジャー");

INSERT INTO users(user_name,pass,nickname) VALUES ("c0119360fd","yuruyuru2020","のすけだよ☆");
INSERT INTO users(user_name,pass,nickname) VALUES ("user1","password_123","とあるユーザー");
INSERT INTO users(user_name,pass,nickname) VALUES ("admin","password_123","管理者");

INSERT INTO shop(gerne_id,prefecture_id,shop_name,postcode,address) VALUES (1,13,"らーめん大 蒲田店",1440052,"大田区蒲田５丁目１−５");
INSERT INTO shop(gerne_id,prefecture_id,shop_name,postcode,address) VALUES (1,13,"八代目 哲麺 めじろ台店",1930833 ,"八王子市めじろ台２丁目６５−６");
INSERT INTO shop(gerne_id,prefecture_id,shop_name,postcode,address) VALUES (1,13,"ラーメン雷 東京本丸店",1000005 ,"千代田区丸の内１丁目９−１ JR東日本東京駅構内 グランスタ東京1F");
INSERT INTO shop(gerne_id,prefecture_id,shop_name,postcode,address) VALUES (1,13,"屋台拉麺一’s 稲毛本店",2630043 ,"千代田区丸の内１丁目９−１ 千葉県千葉市稲毛区小仲台２丁目３−３");
INSERT INTO shop(gerne_id,prefecture_id,shop_name,postcode,address) VALUES (2,44,"ことり",8721611,"大分県国東市国見町向田１８９３−６");
INSERT INTO shop(gerne_id,prefecture_id,shop_name,postcode,address) VALUES (3,22,"三島スカイウォーク",4110012,"静岡県三島市笹原新田３１３");

INSERT INTO photo_data(user_id,shop_id,photo_url,comment) VALUES (1,1,"1_5081f1090448f30c5de8405a4ed37dbb31b55815acef3b663bbe5746a7764920.jpg","ラーメン大はチェーン店なので、蒲田以外にもありますが<br>とても美味しいラーメン屋です！ <br><br>二郎系のお店に入店した時は野菜マシマシでお願いします！<br>と言ってガッツリ食べましょう！");
INSERT INTO photo_data(user_id,shop_id,photo_url,comment) VALUES (1,2,"1_e9485b188b6f2d8c5a3aab06bbae8701e14eb0a083ddfcc417b6d3e6bb1192b1.jpg","豚骨好きにはたまらない！！<br>素晴らしい味です！");
INSERT INTO photo_data(user_id,shop_id,photo_url,comment) VALUES (1,6,"1_f046e6615ef915c7b64d81d9014c68a213440c4ff1301a7eaaaa1a895174462f.jpg","箱根に遊びに行くついでに寄りました〜<br><br>ジップスライダーって言う<br>爽快☆気持ちいい☆テンションマックス☆<br>になる乗り物が入場料金＋2000円で楽しめます！");
INSERT INTO photo_data(user_id,shop_id,photo_url,comment) VALUES (1,3,"1_6351af89b69b02f107677371791176110af8e16b5948b1dd59626579d4988f2c.jpg","東京駅構内にあります。<br><br>松戸のつけ麺「富田」の系列店で、味は最高です！<br><br>めちゃくちゃ並んでいるので、時間がある時に行くのをおす<br>すめします！");
INSERT INTO photo_data(user_id,shop_id,photo_url,comment) VALUES (1,5,"1_a5f0db3ea1cabebcac417b6dcb9e6a17c4c0b707429b8d42ccf2e63b9d82f182.jpg","大分県にあるカフェです。<br><br>車でしかいけませんが、大分空港で車を借りて<br>中津に移動する際はぜひ立ち寄って見てください！<br><br>海が見えるおしゃれなカフェですよ〜〜");
INSERT INTO photo_data(user_id,shop_id,photo_url,comment) VALUES (1,4,"1_57981bc10b5300fc770c938709d8fc0a9da2fd4344e54e94676d965247b33a47.jpg","高校生のときに、部活帰り良く食べていました〜<br><br>JR稲毛駅の近くにあります。<br><br>若干値段が高いですが、店舗がキレイで<br>めちゃくちゃ美味しかった覚えがあります。<br><br>現在は店舗2階が焼き肉屋になっています！！");

INSERT INTO bookmark(user_id,photo_id) VALUES (2,1);
INSERT INTO bookmark(user_id,photo_id) VALUES (2,2);
INSERT INTO bookmark(user_id,photo_id) VALUES (2,4);
INSERT INTO bookmark(user_id,photo_id) VALUES (2,5);




-- ブックマーク数を表示するview
create view view_bookmark_count as
select photo_id , count(photo_id) as bookmark_count from bookmark group by photo_id;

-- v
CREATE VIEW view_all_photo AS 
SELECT shop_name,photo_data.photo_id,photo_url,shop.prefecture_id,gerne_id,comment,user_id,address,prefecture_name,photo_data.shop_id,IFNULL(bookmark_count,0) AS bookmark_count FROM photo_data 
INNER JOIN shop 
ON photo_data.shop_id = shop.shop_id
INNER JOIN prefectures
ON prefectures.prefecture_id = shop.prefecture_id
LEFT JOIN view_bookmark_count
ON photo_data.photo_id = view_bookmark_count.photo_id
WHERE shop.delete_flag = 0
AND photo_data.delete_flag = 0
AND  closed = 0;


-- v
CREATE VIEW view_shop_select AS 
SELECT shop_id,gerne_name,prefecture_name,shop_name,address
FROM shop
INNER JOIN prefectures
ON shop.prefecture_id = prefectures.prefecture_id
INNER JOIN gerne
ON shop.gerne_id = gerne.gerne_id
WHERE delete_flag = 0
AND closed = 0
ORDER BY shop.shop_id desc;

-- 竣のview
CREATE VIEW view_photo_details as
SELECT photo_id,photo_data.shop_id,gerne_name,shop_name,prefecture_name,postcode,address,photo_url,comment FROM shop
INNER JOIN photo_data
ON shop.shop_id = photo_data.shop_id 
INNER JOIN prefectures
ON shop.prefecture_id = prefectures.prefecture_id
INNER JOIN gerne
ON shop.gerne_id = gerne.gerne_id
WHERE photo_data.delete_flag = 0
AND shop.delete_flag = 0
AND shop.closed = 0;


-- view_all_photo に ブックマーク数を表示（仮）
CREATE VIEW view_all_photo_2 AS 
SELECT shop_name,photo_data.photo_id,photo_url,shop.prefecture_id,gerne_id,comment,user_id,address,prefecture_name,photo_data.shop_id,IFNULL(bookmark_count,0) FROM photo_data 
INNER JOIN shop 
ON photo_data.shop_id = shop.shop_id
INNER JOIN prefectures
ON prefectures.prefecture_id = shop.prefecture_id
LEFT JOIN view_bookmark_count
ON photo_data.photo_id = view_bookmark_count.photo_id
WHERE shop.delete_flag = 0
AND photo_data.delete_flag = 0
AND  closed = 0;

-- ↓メモです。


drop table bookmark;
drop table gerne;
drop table photo_data;
drop table prefectures;
drop table shop;
drop table users;


-- dbを作成する時のメモです。
-- ローカルで作成用(サーバーでは実行しない)
CREATE DATABASE g15 CHARACTER SET utf8 COLLATE utf8_general_ci;
USE g15;
CREATE USER 'g15'@'localhost';
GRANT ALL ON *.* to 'g15'@'localhost';
SET PASSWORD FOR 'g15'@'localhost' = PASSWORD('g15');


CREATE DATABASE g15_test01 CHARACTER SET utf8 COLLATE utf8_general_ci;
USE g15_test01;
CREATE USER 'g15_test01'@'localhost';
GRANT ALL ON *.* to 'g15_test01'@'localhost';
SET PASSWORD FOR 'g15_test01'@'localhost' = PASSWORD('g15');




---
SELECT shop_name,photo_id,photo_url,prefecture_id,gerne_id
FROM view_all_photo
WHERE comment LIKE '%気持ちいい%'
OR address LIKE '%気持ちいい%'
OR shop_name LIKE '%気持ちいい%'
OR prefecture_name LIKE '%気持ちいい%';

select shop_name,photo_id,photo_url,prefecture_id,gerne_id from view_all_photo where prefecture_id=22 where comment LIKE '%気持ちいい%' OR address LIKE '%気持ちいい%' OR shop_name LIKE '%気持ちいい%' OR prefecture_name LIKE '%気持ちいい%'


