# bitinterview
for interview

# How to install
### 1. สร้าง database ชื่อ 
>bitinterviewdb
### 2. run คำสั่งใน terminal เพื่อติดตั้ง
`composer install`
### 3. แก้ชื่อ file
>.env.example -> .env
### 4. ใน file .env
>DB_DATABASE=bitinterviewdb
### 5. run คำสั่งใน terminal เพื่อ generate key
`php artisan key:generate`
### 6. run คำสั่งใน terminal ด้านล่าง เพื่อสร้าง table
`php artisan migrate`
### 7. run คำสั่งใน terminal ด้านล่าง เพื่อ seed ข้อมูล
`php artisan db:seed`
### 8. run คำสั่งใน terminal ด้านล่าง เพื่อ run project
`php artisan serv`

## ER Diagram
![bitinterviewdb](https://user-images.githubusercontent.com/99132826/161448830-ff3b4cc6-9e9a-46c8-8c15-83ab832ff311.png)
