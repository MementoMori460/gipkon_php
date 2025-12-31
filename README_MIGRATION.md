# GIPKON PHP Migration Status

## 🚀 Proje Durumu
Bu proje, Next.js tabanlı `gipkon` web sitesinin **bağımsız PHP (Native)** versiyonudur.
Tüm veriler `data/` klasöründeki JSON dosyalarından okunur. Veritabanı gerektirmez.

## ✅ Tamamlananlar
- **Çekirdek Yapı:** `functions.php`, `router.php`, `includes/simple_smtp.php` ve varlık yönetimi (`BASE_URL` ile alt dizin/root uyumluluğu).
- **Varlıklar:** `assets/` (CSS/JS) ve `images/`, `uploads/` klasörleri Next.js projesinden kopyalandı.
- **Ana Sayfa:** `index.php` Next.js tasarımıyla birebir eşleşti (Slider, Stats, Hizmetler, Çözümler, Referanslar, CTA).
- **Kurumsal Sayfalar:** Hakkımızda, Vizyon/Misyon vb.
- **Listeleme Sayfaları:** Hizmetler, Çözümler, Projeler, Referanslar.
- **Detay Sayfaları:** Hizmet Detay (`/hizmetler/[slug]`), Çözüm Detay (`/cozumler/[slug]`), Proje Detay (`/projeler/[slug]`).
- **İletişim:** İletişim formu SMTP destekli hale getirildi (`/iletisim`).
- **Admin Paneli (Temel):**
  - Giriş (`/admin/login`)
  - Dashboard (`/admin`)
  - Kenar Çubuğu (Sidebar)
  - Medya/Katalog/SSS/Referans/Proje/Hizmet Yönetimi Sayfaları
  - Ayarlar Sayfası (SMTP ayarları eklendi)
  
## 🚧 Sıradaki Yapılacaklar (Next Steps)

1.  **SEO & Meta Etiketleri:** Tüm sayfalar için dinamik `<title>` ve `<meta description>` ayarlarının `render_header` fonksiyonuna parametre olarak geçilmesi.
2.  **Güvenlik:** Admin paneli için basit oturum kontrolü var ama CSRF koruması ve girdi temizliği (input sanitization) daha kapsamlı hale getirilebilir.
3.  **Performans:** Görsel optimizasyonları ve cache mekanizmaları.

## 🛠 Çalıştırma
Terminalde şu komutla başlatabilirsiniz:
```bash
php -S localhost:8080 router.php
```
*Not: `router.php` tüm istekleri karşılar ve yönlendirir.*
