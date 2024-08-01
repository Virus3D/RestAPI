# Symfony REST API (User)

### Создание пользователя:
**POST** /api/users/add

*Запрос:*
```json
{"username": "User Name", "email": "User@email", "password": "password"}
```
*Ответ (успех):* 
```json
{"status":201,"success":"User added successfully"}
```
*Ответ (не удача):* 
```json
{"status":422,"success":"Data no valid"}
```

### Обновление информации пользователя:
**PUT** /api/users/{id}

*Header* 
```
[Authorization: Bearer TOKEN]
```
*Запрос:* 
```json
{"username": "User Name", "email": "User@email", "password": "password"}
```
*Ответ (успех):* 
```json
{"status":201,"success":"User updated successfully"}
```
*Ответ (не удача):* 
```json
{"code": 401, "message": "Invalid credentials."}
```
```json
{"status":422,"success":"Data no valid"}
```
```json
{"status":404,"success":"User not found"}
```

### Удаление пользователя:
**DELETE** /api/users/{id}

*Header* 
```
[Authorization: Bearer TOKEN]
```
*Ответ (успех):* 
```json
{"status":200,"success":"User deleted successfully"}
```
*Ответ (не удача):* 
```json
{"code": 401, "message": "Invalid credentials."}
```
```json
{"status":404,"success":"User not found"}
```

### Получить информацию о пользователе:
**GET** /api/users/{id}

*Header* 
```
[Authorization: Bearer TOKEN]
```
*Ответ (успех):* 
```json
{"username":"root","email":"root@mail.ru"}
```
*Ответ (не удача):* 
```json
{"code": 401, "message": "Invalid credentials."}
```
```json
{"status":404,"success":"User not found"}
```

### Получить информацию о всех пользователях:
**GET** /api/users

*Header* 
```
[Authorization: Bearer TOKEN]
```
*Ответ (успех):*
```json
[{"username":"root","email":"root@mail.ru"}]
```

### Авторизация пользователя
**POST** /api/login

*Запрос:* 
```json
{"username": "User Name", "password": "password"}
```
*Ответ (успех):* 
```json
{"token": "TOKEN"}
```
*Ответ (не удача):* 
```json
{"code": 401, "message": "Invalid credentials."}
```
