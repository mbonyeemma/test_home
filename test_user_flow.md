# Complete User Registration and Login Flow Test Guide

## Problem Identified:
- **Registration** saves to `restrackself_reg` table (MobileAppRegistration model)
- **Login** tries to authenticate against `users` table (User model)
- **No connection** between the two tables
- **Users remain inactive** (`isactive = 0`) after registration

## Solution Implemented:
1. **Registration** → `restrackself_reg` table (pending)
2. **Approval** → Transfers user to `users` table (active)
3. **Login** → Authenticates against `users` table (success)

## Complete Test Flow:

### Step 1: Register a New User
```bash
curl -X POST http://localhost:8090/api/restrack_new/register_user/ \
  -H "Content-Type: application/json" \
  -d '{
    "username": "+256701234567",
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "telephone_number": "+256701234567",
    "hubid": "1",
    "role": "rider",
    "device_name": "iPhone 12",
    "device_serial": "ABC123",
    "defensive_driving": "Yes"
  }'
```

**Expected Response:**
```json
{
  "status": 200,
  "status_desc": "The User Saved has been successfully, Awaiting Approval"
}
```

### Step 2: Check User Status (Should be pending)
```bash
curl http://localhost:8090/api/restrack_new/check_user_status/+256701234567
```

**Expected Response:**
```json
{
  "status": 200,
  "data": {
    "username": "+256701234567",
    "in_registration_table": true,
    "registration_status": "pending",
    "in_users_table": false,
    "can_login": false
  }
}
```

### Step 3: Try Login (Should FAIL)
```bash
curl -X POST http://localhost:8090/api/restrack/login/ \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "username": "+256701234567",
    "password": "cGFzc3dvcmQxMjM="
  }'
```

**Expected Response:**
```json
{
  "status": 501,
  "status_desc": "Login failed, check your password and username"
}
```

### Step 4: Get Pending Registrations
```bash
curl http://localhost:8090/api/restrack_new/pending_registrations/
```

**Expected Response:**
```json
{
  "status": 200,
  "data": [
    {
      "id": 1,
      "username": "+256701234567",
      "name": "John Doe",
      "email": "john@example.com",
      "isactive": 0
    }
  ]
}
```

### Step 5: Approve User (Transfer to users table)
```bash
curl -X POST http://localhost:8090/api/restrack_new/approve_user/1
```

**Expected Response:**
```json
{
  "status": 200,
  "status_desc": "User approved and transferred successfully. User can now login.",
  "user_id": 1,
  "username": "+256701234567"
}
```

### Step 6: Check User Status (Should be approved)
```bash
curl http://localhost:8090/api/restrack_new/check_user_status/+256701234567
```

**Expected Response:**
```json
{
  "status": 200,
  "data": {
    "username": "+256701234567",
    "in_registration_table": true,
    "registration_status": "approved",
    "in_users_table": true,
    "can_login": true
  }
}
```

### Step 7: Try Login (Should SUCCEED)
```bash
curl -X POST http://localhost:8090/api/restrack/login/ \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "username": "+256701234567",
    "password": "cGFzc3dvcmQxMjM="
  }'
```

**Expected Response:**
```json
{
  "status": 200,
  "status_desc": "Successfully logged in",
  "user": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "username": "+256701234567",
      "hubid": 1,
      "telephone_number": "+256701234567",
      "roles": [...]
    }
  ]
}
```

## Key Points:

1. **Registration** creates user in `restrackself_reg` table with `isactive = 0`
2. **Approval** transfers user to `users` table and sets `isactive = 1`
3. **Login** only works for users in `users` table
4. **Password** is bcrypted during registration and transferred as-is
5. **Username** must be unique in both tables

## Troubleshooting:

- If login still fails after approval, check if user exists in `users` table
- Verify password is correctly bcrypted and transferred
- Ensure username matches exactly between registration and login
- Check database permissions and table structure 