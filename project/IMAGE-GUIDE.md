# 📸 Image Upload Guide for Coffee Haven

## 📁 Folder Structure

```
c:\xampp\htdocs\project\assets\images\
├── products\          ← Put ALL product images here
├── placeholder.png    ← Already exists (fallback image)
├── hero-bg.png       ← Already exists (homepage background)
└── about.png         ← Already exists (about section)
```

## ☕ Product Images Needed

Place these **20 images** in: `c:\xampp\htdocs\project\assets\images\products\`

### Hot Coffee (5 images) ☕
1. `espresso.png` - Espresso shot
2. `americano.png` - Black coffee
3. `cappuccino.png` - Coffee with foam
4. `latte.png` - Milky coffee
5. `mocha.png` - Chocolate coffee

### Cold Coffee (4 images) 🧊
6. `iced-americano.png` - Iced black coffee
7. `iced-latte.png` - Iced milk coffee
8. `cold-brew.png` - Cold brew coffee
9. `frappuccino.png` - Blended iced coffee

### Specialty Drinks (2 images) ✨
10. `caramel-macchiato.png` - Caramel coffee
11. `vanilla-latte.png` - Vanilla flavored latte

### Snacks (5 images) 🥐
12. `croissant.png` - French pastry
13. `blueberry-muffin.png` - Muffin
14. `sandwich.png` - Sandwich
15. `bagel.png` - Bagel
16. `cookies.png` - Cookies

### Desserts (4 images) 🍰
17. `cheesecake.png` - Cheesecake slice
18. `brownie.png` - Chocolate brownie
19. `tiramisu.png` - Tiramisu dessert
20. `donut.png` - Donut

---

## 🎯 Quick Steps

### Option 1: Using File Explorer
1. Open folder: `c:\xampp\htdocs\project\assets\images\products\`
2. Copy your product images there
3. **Rename** them to match the exact names above (e.g., `espresso.png`)

### Option 2: Using PowerShell
```powershell
# Navigate to products folder
cd "c:\xampp\htdocs\project\assets\images\products\"

# Copy your images here and rename them
Copy-Item "C:\Downloads\my-coffee-photo.jpg" -Destination "espresso.png"
```

---

## 📐 Image Requirements

✅ **Format**: PNG (recommended) or JPG  
✅ **Size**: 500x500px to 1000x1000px (square images work best)  
✅ **File Size**: Keep under 500KB each for fast loading  
✅ **Naming**: Must match EXACTLY (case-sensitive on some servers)

---

## 🔍 How the System Works

When you visit the website:
1. PHP looks for: `assets/images/products/espresso.png`
2. If found → Shows your image ✅
3. If NOT found → Shows `placeholder.png` (gray placeholder) ⚪

**Example in menu.php:**
```php
<img src="assets/images/products/<?php echo $product['image']; ?>" 
     alt="<?php echo $product['name']; ?>"
     onerror="this.src='assets/images/placeholder.png'">
```

---

## 🎨 Where to Find Free Coffee Images

### Free Stock Photo Sites:
- **Unsplash**: https://unsplash.com/s/photos/coffee
- **Pexels**: https://www.pexels.com/search/coffee/
- **Pixabay**: https://pixabay.com/images/search/coffee/

### Search Terms:
- "espresso cup"
- "iced latte glass"
- "cappuccino top view"
- "croissant plate"
- "chocolate brownie"

---

## 🛠️ Testing Your Images

1. **Add images** to `assets\images\products\`
2. **Open browser**: http://localhost/project/menu.php
3. **Check**: Images should appear on product cards
4. If you see gray placeholder → Check filename spelling

---

## 📝 Update Product Images in Database

If you want to change image names in the database:

```sql
-- Example: Change espresso image
UPDATE products SET image = 'new-espresso.png' WHERE name = 'Espresso';

-- Or update all at once via phpMyAdmin
-- Navigate to: http://localhost/phpmyadmin
-- Select: coffee_shop database
-- Edit: products table
```

---

## 💡 Pro Tips

1. **Use consistent aspect ratio** (1:1 square) for all products
2. **White background** or transparent PNG looks professional
3. **Name files in lowercase** to avoid issues: `espresso.png` not `Espresso.PNG`
4. **Test one image first** before adding all 20

---

## 🚀 Quick Test

Want to test if it works? Try this:

1. Download any coffee image from Google
2. Save it as: `espresso.png`
3. Put it in: `c:\xampp\htdocs\project\assets\images\products\`
4. Refresh: http://localhost/project/menu.php
5. You should see your image! ✨

---

## ❓ Troubleshooting

**Problem**: Images don't show  
**Solution**: 
- ✅ Check file exists in correct folder
- ✅ Verify exact filename (case-sensitive)
- ✅ Clear browser cache (Ctrl+F5)
- ✅ Check image file isn't corrupted

**Problem**: Only placeholder shows  
**Solution**:
- ✅ Filename doesn't match database
- ✅ File extension wrong (.jpg vs .png)
- ✅ File permissions issue (unlikely on Windows)

---

## 📊 Current Database Image Names

All product images in database use `.png` extension:
```
espresso.png
americano.png
cappuccino.png
latte.png
mocha.png
iced-americano.png
iced-latte.png
cold-brew.png
frappuccino.png
caramel-macchiato.png
vanilla-latte.png
croissant.png
blueberry-muffin.png
sandwich.png
bagel.png
cookies.png
cheesecake.png
brownie.png
tiramisu.png
donut.png
```

Just match these names exactly! 🎯
