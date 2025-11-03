# Migration Deployment Guide for Production Server

## Problem
New migration files added locally are not visible in the production Docker container because:
1. The Dockerfile copies files at **BUILD TIME** (`COPY . .`)
2. New migrations added after the image was built won't be in the container
3. Even though docker-compose has volume mounts, they need proper sync

## Solution: Deploy Migrations to Production

### Step 1: Push Your Code to Git
```bash
git add .
git commit -m "Add package_pickup_requests and test migrations"
git push origin main
```

### Step 2: On Production Server - Pull Latest Code
```bash
ssh ec2-user@54.243.89.55
cd /home/ec2-user/test_home
git pull origin main
```

### Step 3: Rebuild the Docker Image (Important!)
This ensures new migration files are copied into the image:
```bash
docker-compose build app --no-cache
```

### Step 4: Restart the Container
```bash
docker-compose down
docker-compose up -d
```

### Step 5: Verify Migration Files Are Present
```bash
docker-compose exec app ls -la /var/www/database/migrations/ | grep -E "(test_notify|package_pickup)"
```

You should see:
- `2025_11_03_000000_create_test_notify_riders_table.php`
- `2025_10_28_000001_create_package_pickup_requests_table.php`

### Step 6: Run Migrations
```bash
docker-compose exec app php artisan migrate
```

You should see:
```
✅ TEST MIGRATION RUNNING - MIGRATIONS ARE WORKING! ✅
✅ Test table 'test_notify_riders_migration' created successfully!

Migrating: 2025_11_03_000000_create_test_notify_riders_table
Migrated:  2025_11_03_000000_create_test_notify_riders_table

🚀 CREATING PACKAGE_PICKUP_REQUESTS TABLE FOR NOTIFY RIDERS FEATURE 🚀

Migrating: 2025_10_28_000001_create_package_pickup_requests_table
Migrated:  2025_10_28_000001_create_package_pickup_requests_table
```

### Step 7: Verify Tables Were Created
```bash
docker-compose exec -T db mysql -uroot -proot hub_db -e "SHOW TABLES LIKE '%test_notify%';"
docker-compose exec -T db mysql -uroot -proot hub_db -e "SHOW TABLES LIKE '%package_pickup%';"
```

### Step 8: Clean Up Test Table (Optional)
Once verified working, remove the test table:
```bash
docker-compose exec -T db mysql -uroot -proot hub_db -e "DROP TABLE IF EXISTS test_notify_riders_migration;"
```

## Alternative: Quick Deployment Without Full Rebuild

If you don't want to rebuild the entire image, you can use volume mounts:

### Option A: Copy Migration Files Directly
```bash
# On production server
docker cp database/migrations/2025_10_28_000001_create_package_pickup_requests_table.php laravel56-app:/var/www/database/migrations/
docker cp database/migrations/2025_11_03_000000_create_test_notify_riders_table.php laravel56-app:/var/www/database/migrations/
```

### Option B: Use rsync to Sync Files
```bash
# From your local machine
rsync -avz database/migrations/ ec2-user@54.243.89.55:/home/ec2-user/test_home/database/migrations/
```

## Troubleshooting

### If migrations still don't show up:
1. **Check if files exist on host:**
   ```bash
   ls -la /home/ec2-user/test_home/database/migrations/
   ```

2. **Check if files exist in container:**
   ```bash
   docker-compose exec app ls -la /var/www/database/migrations/
   ```

3. **Check volume mounts:**
   ```bash
   docker inspect laravel56-app | grep -A 10 Mounts
   ```

4. **Clear Laravel cache:**
   ```bash
   docker-compose exec app php artisan cache:clear
   docker-compose exec app php artisan config:clear
   docker-compose exec app php artisan route:clear
   ```

## Future Deployments

For all future migrations:
1. **Always rebuild** the Docker image after adding new migrations
2. Or use a CI/CD pipeline that automatically rebuilds on code changes
3. Or consider using volume mounts for development and proper builds for production

---

**Current Status**: 
- ✅ Test migration created: `2025_11_03_000000_create_test_notify_riders_table.php`
- ✅ Package pickup migration ready: `2025_10_28_000001_create_package_pickup_requests_table.php`
- 🎯 Next: Deploy to production using steps above

