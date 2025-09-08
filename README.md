# お問い合わせフォーム

## 環境構築

### Docker ビルド

1. `git clone git@github.com:seki0603/flea-market.git`
2. cd flea-market
3. mkdir docker/mysql/data
4. docker-compose up -d --build

＊MySQL は、OS によって起動しない場合があるのでそれぞれの PC に合わせて docker-compose.yml ファイルを編集してください。
<br>

### Laravel 環境構築

1. docker-compose exec php bash
2. composer install
3. .env.example ファイルから.env を作成し、環境変数を変更
4. php artisan key:generate
5. php artisan migrate
6. php artisan db:seed
7. php artisan storage:link
   <br>

## 使用技術

- PHP 8.1.3
- Laravel 8.83.29
- MySQL 8.0.26
- nginx 1.21.1
  <br>

## ER 図

## 補足事項

- メール送信は MailHog (http://localhost:8025/) で確認可能

## URL

- 開発環境：http://localhost/
- phpMyAdmin：http://localhost:8080/
