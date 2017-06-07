PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE "servicetypes" ("id" integer not null primary key autoincrement, "name" varchar not null, "display_name" varchar not null, "description" varchar not null, "icon_url" varchar not null, "display_order" integer not null, "client_price" float not null default '0');
INSERT INTO "servicetypes" VALUES(1,'jump','Vehicle Boost','Dead Battery? We will immediately get you running and on your way!','/img/icons/battery-icon.png',1,55.0);
INSERT INTO "servicetypes" VALUES(2,'tire','Tire Change','Have a flat? We will change it for you as you stay warm and enjoy the tunes!','/img/icons/tire-icon.png',2,55.0);
INSERT INTO "servicetypes" VALUES(3,'fuel','Fuel Delivery','Ran out of gas? Don’t worry we will bring you 10 Litres right away!','/img/icons/fuel-icon.png',3,55.0);
INSERT INTO "servicetypes" VALUES(4,'lockout','Locked Keys Inside','Don’t stress - We will be right there to let you in!','/img/icons/lockout-icon.png',4,55.0);
INSERT INTO "servicetypes" VALUES(5,'tow','Towing','It’s quick and easy, we will immediately move your vehicle to where you need it.','/img/icons/tow-icon.png',5,55.0);
COMMIT;
