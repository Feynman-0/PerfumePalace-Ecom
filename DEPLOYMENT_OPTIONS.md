# Quick Client Demo Setup - ngrok (Instant!)

## Option 1: ngrok (Share your localhost - 2 minutes!)

1. Download ngrok: https://ngrok.com/download
2. Sign up for free account
3. Run in terminal:
   ```
   ngrok http 8000
   ```
4. Share the ngrok URL (e.g., https://abc123.ngrok.io) with client
5. Client can access your local Bagisto immediately!

✅ Pros: Instant, no deployment needed
⚠️ Cons: Only works while your computer is on, session expires

---

## Option 2: Railway.app (Best Free Deployment - 20 minutes)

### Steps:

1. **Sign up**: https://railway.app (use GitHub)

2. **Prepare your code**:
   ```bash
   # Add Procfile to D:\Bagisto\
   echo "web: php artisan serve --host=0.0.0.0 --port=$PORT" > Procfile
   
   # Add nixpacks.toml
   echo "[phases.setup]
   nixPkgs = ['php82', 'php82Extensions.pdo', 'php82Extensions.pdo_mysql', 'composer']
   
   [phases.build]
   cmds = ['composer install --no-dev --optimize-autoloader']
   
   [start]
   cmd = 'php artisan serve --host=0.0.0.0 --port=$PORT'" > nixpacks.toml
   ```

3. **Push to GitHub**:
   ```bash
   git init
   git add .
   git commit -m "Initial commit"
   git remote add origin YOUR_GITHUB_REPO_URL
   git push -u origin main
   ```

4. **Deploy on Railway**:
   - Click "New Project"
   - Select "Deploy from GitHub repo"
   - Select your Bagisto repo
   - Add MySQL database (from Railway's plugins)
   - Add environment variables from your .env
   - Railway auto-deploys!

5. **Get URL**: Railway gives you https://your-app.up.railway.app

---

## Option 3: Render.com (Free but sleeps)

Similar to Railway but service sleeps after 15 min inactivity.

---

## RECOMMENDED FOR CLIENT DEMO:

**Use ngrok RIGHT NOW** (2 minutes):
1. Download ngrok
2. Run: `ngrok http 8000`
3. Share the https URL with client
4. Done!

**For longer demo (days/weeks):**
Deploy to Railway or Oracle Cloud Free Tier.
