# 勤怠管理システム
登録したユーザーが勤務開始と勤務終了を、それぞれ1日1回打刻できます。
休憩は1日に何度も打刻できます。
一覧表示ページでは、勤務開始時刻、勤務終了時刻、休憩時間、勤務時間を表示することができます。

## 作成した目的
勤怠管理を簡単にできるように作成しました。

## アプリケーションURL
- 開発環境：http://localhost/
- phpMyAdmin : http://localhost:8080/

## 機能一覧
- ログイン機能
- ユーザー登録機能
- 勤怠打刻機能
- 勤怠管理一覧表示機能

## 環境構築
Dockerビルド
1. git clone リンク
2. docker-compose up -d —build

Laravel環境構築
1. docker-compose exec php bash
2. composer install
3. .env.exampleファイルから.envを作成し、環境変数を変更
4. php artisan:key generate
5. php artisan migrate
6. php artisan db:seed

## 使用技術
- PHP 7.4.9
- Laravel Framework 8.83.8
- MySQL  8.0.26

##テーブル設計
![image](https://github.com/nan888999/attendance/assets/167194215/a725e2c6-aa9a-4b41-b789-a56ec606232c)

## ER図
![image](https://github.com/nan888999/attendance/assets/167194215/6b25694d-d0b2-47cb-a73c-a1bcc0ed324a)


