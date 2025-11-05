#!/bin/bash

echo "=========================================="
echo "🔧 RESTRACK Backend Setup Script"
echo "=========================================="

# 1. Fix Composer PackageManifest error
echo ""
echo "1️⃣ Fixing Composer PackageManifest..."
docker-compose exec app composer dump-autoload --no-scripts
docker-compose exec app php artisan package:discover --ansi

# 2. Add Firebase credentials to .env
echo ""
echo "2️⃣ Adding Firebase credentials to .env..."
docker-compose exec app bash -c "cat >> .env << 'ENVEOF'

# Firebase Cloud Messaging Configuration
FIREBASE_PROJECT_ID=restrack-f90a3
FIREBASE_PRIVATE_KEY_ID=7ce643513c551a54a85a0fff664349a9c7eba02d
FIREBASE_PRIVATE_KEY=\"-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCp8dUxiWzF7IsO
JCGKHDwZ56hFfA9HCNEDsmI1Hsl6NZbQYKIwnKrj/9HwWlyF0HDgwJrpqYvs3tWi
7jVn7vsbGWDCfhuWaukfWqdE4s6x/9Q/1TxmT2d7Ir4eYsjvS41lIuSGjgAs8D+1
bxQdL/lSZO8w/mNbuhPMA/uBB23s+YD4crwimYJzQbKa9tu8Vh4HlKJVygCZ5H6u
huMi9pMkL6bIs1BS/aIVs0v+l7UONNYIsz8MUl6g6d1x9htP5BSh0rQLYIPCfOPw
8a76Qjz2joLe6xaWbojuP36OvlrZLeVbuMvLACgyxazJWofASdBtbPItc6Ym/Ozj
Fju3Z3ulAgMBAAECggEAEztXVYZIRXO53s1O8Fq2oHXbUJW0AuVoBeOTgpBr24Uh
/o3Y/OhV3HxwTSNksg3/ICgbv9Kcj58+DPSpHYxpZU0vaoMr6w9JN2+iW0TRgEpD
wj+G2MsmmkQ5wKWMOKxToXzb4LBEK9G6W/VokM8Dh4P9n41CQDikEePne9gT5Au+
v879f8bEBjCo+n/8qmy/HODAut8SioTFxi/wyADmXBdi8G4ZV056FMsgoiHVGoGX
IbNPRk0iK+7yqSzArfqhCjULHe2Ju4CG4d6/SA1Au3i/OTvNyiB53zRyDPEAGqso
dOBHU2VWgDkQ13HfauRnVQ5YC4FyON8Q/FcrH5330QKBgQDpTB85xySPTztD6MmT
SRzuLmvKW144it45VZhaKmwSNh0jLR3hBxHb9orQ8YKS5Tp/u3CK18z4ca5lKTvu
ODzDMlXVqQnK34TSHiBzDUWn3Hgle0exl3JW/TR8m8asZ10aAnJAwQol3rLM+SEt
15jYdu8yWERmRPW393/QXii9jQKBgQC6e3m6E6ape0vthofRKQN1e7mW8VUbPjdu
X3uTPeXgY4b+JtOXdgntzsJltdK7C1IPFPbemzJrcjWq6s7JxvU9snL32/BjoG1m
J1RK07fzmnn1bDtxocLYFgAbxa25z/QRKNJrOZKIaPjLQfya3KsGGWZTXcVpHtsD
Bzsaq8p0eQKBgGS6QSEpIlfd1bDUaXP2NU+BK3kLSfsPujL3CfikKFUUmC//4s6t
xsA6CmV8YOwbnM5Zl5Xa5ty9+JYk34NTNKjyqqy9d0TojOhLqacDK/f18Mn+GE5N
HUkBug+zEmyNlF0OgVYEAuWm6XwmdHTeiVeswknyYlXloFH1wnGstdphAoGAZhMq
aUlSY9jikcye0UWS6B7mUpIdFMF0lAzd1pX+G1o/TSSxk2mAO9R+IjBpfgrPSwQd
sXHgImIssDbBJD+sg64HMlcIeXAaEd2bTS4gtc4rzcQFschqn99DBfCpjFkg7rea
niiwZcyXyqJ/A8GgN8F1elKMuWKzXS/7ETlRo3kCgYEAvml2H9yMFmflyoRZUNhi
sb+WVuZR93XLnKxNpMIlU9bW7/VicwZdFQ+KtVCPQqzMpWScHqP3Q8rSjfdhZ8Px
np6hfKmhV3KY8y7/yKCjMU8RuZr0CgVon1FPDDinZAeZOt07uiJOgpNl/yWx3IIa
s63yp/JDHzny7WHoURYiYgs=
-----END PRIVATE KEY-----
"
FIREBASE_CLIENT_EMAIL=firebase-adminsdk-fbsvc@restrack-f90a3.iam.gserviceaccount.com
FIREBASE_CLIENT_ID=110966975864077193854
ENVEOF"

# 3. Clear all caches
echo ""
echo "3️⃣ Clearing Laravel caches..."
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan view:clear
docker-compose exec app php artisan route:clear

# 4. Restart app container
echo ""
echo "4️⃣ Restarting app container..."
docker-compose restart app

echo ""
echo "=========================================="
echo "✅ Setup Complete!"
echo "=========================================="
echo ""
echo "Now testing push notification..."
echo ""

# 5. Test push notification
docker-compose exec -T app php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
\$fcmService = app(\App\Services\FcmService::class);
\$fcmToken = 'eAaOWX1MRBmSzos45m4vsV:APA91bEf7DlDflv9qzH075birTCGUo8UAd_5Z3WvSuVXNn5cgW6fxi33tSWvQXBaykFLxRXEuFXuygnzhuP620Q0QtgtOHPJsDDLQKsJqzCetfEFtUB4rK4';
echo '🔔 Sending test push notification...' . PHP_EOL;
\$result = \$fcmService->sendPushNotification(
    \$fcmToken,
    '🎉 Push Notification Working!',
    'Backend setup complete and push notifications are functional!',
    ['test' => 'final_local', 'time' => (string)time()]
);
echo PHP_EOL . (\$result ? '✅✅✅ SUCCESS! Check your device! ✅✅✅' : '❌ FAILED - Check logs') . PHP_EOL;
"

echo ""
echo "🎯 Next steps:"
echo "   1. If notification received: git push origin prod"
echo "   2. Then run the same setup on production server"
echo ""

