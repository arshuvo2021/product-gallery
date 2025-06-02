
````md
# 📷 Product Gallery Manager

**Product Gallery Manager** is a Laravel-based application to manage products and their image galleries. Easily add, update, and delete products with support for uploading multiple images using standard forms or AJAX.

---

## 🚀 Features

- 🛍️ Product CRUD operations  
- 📁 Upload multiple images per product (minimum 3)  
- ❌ Remove selected images  
- ⚡ AJAX image upload & drag-and-drop via Dropzone.js  
- ✅ Image validation (JPEG, PNG, WEBP, max 2MB)  
- 🗂️ Image storage handled via Laravel filesystem  

---

## ⚙️ Installation

1. **Clone the repository:**

   ```bash
   git clone https://github.com/arshuvo2021/product-gallery.git
   cd product-gallery
````

2. **Install PHP dependencies:**

   ```bash
   composer install
   ```

3. **Configure `.env` and set your database:**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Run migrations and seed the database with sample products & images:**

   ```bash
   php artisan migrate --seed
   ```

5. **Create symbolic link for image access:**

   ```bash
   php artisan storage:link
   ```

6. **Serve the app locally:**

   ```bash
   php artisan serve
   ```



## 👤 Author

**Md Abdur Rahman Shuvo**
[GitHub Profile](https://github.com/arshuvo2021)

---

## 📝 License

This project is licensed under the [MIT License](LICENSE).

