# MySQL 접속
mysql -uroot shop

# 쇼핑몰 DB 삭제
DROP DATABASE shop;

# 쇼핑몰 DB 생성, 사용
CREATE DATABASE shop;
USE shop;

# table 삭제
DROP TABLE user;
DROP TABLE item;
DROP TABLE itemimg;
DROP TABLE board;
DROP TABLE boardfile;
DROP TABLE purchase;

# table 생성
CREATE TABLE user (
  id           char(20)     NOT NULL PRIMARY KEY,
  pw           char(40)     NOT NULL,
  name         varchar(10)  NOT NULL,
  hp           char(20)     NOT NULL,
  address      varchar(40)  NOT NULL,
  cyber_money  int          NOT NULL DEFAULT 100000,
  cyber_point  int          NOT NULL DEFAULT 0,
  rating       int          NOT NULL DEFAULT 5,
  date         date         NOT NULL
);

CREATE TABLE item (
  name        char(30)      NOT NULL,
  no          int           NOT NULL AUTO_INCREMENT PRIMARY KEY,
  kind        varchar(20)   NOT NULL,
  stock       int           NOT NULL,
  cost        int           NOT NULL
);

CREATE TABLE itemimg (
  item_no     int,
  image_name  varchar(100)  NOT NULL,
  image_path  varchar(100)  NOT NULL
);

CREATE TABLE board (
  no         int           NOT NULL AUTO_INCREMENT PRIMARY KEY,
  title      varchar(200)  NOT NULL,
  contents   text          NOT NULL,
  user_id    char(20)      NOT NULL,
  date       date          NOT NULL,
  page_view  int           NOT NULL DEFAULT 0
);

CREATE TABLE boardfile (
  board_no   int,
  file_name  varchar(100)  NOT NULL,
  real_name  varchar(100)  NOT NULL
);

CREATE TABLE purchase (
  p_no       int          NOT NULL AUTO_INCREMENT PRIMARY KEY,
  i_no       int          NOT NULL,
  u_id       char(20)     NOT NULL,
  u_hp       char(20)     NOT NULL,
  u_address  varchar(40)  NOT NULL
);

# 댓글 table 생성
CREATE TABLE reply (
  no         int           NOT NULL AUTO_INCREMENT PRIMARY KEY,
  board_no   varchar(200)  NOT NULL,
  contents   text          NOT NULL,
  user_id    char(20)      NOT NULL,
  date       date          NOT NULL,
  file_name  varchar(100)  NOT NULL
);

# 댓글 table 내용 확인
SELECT * FROM reply;