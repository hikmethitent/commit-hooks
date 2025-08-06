# CaptainHook Kullanım Rehberi

Bu proje, CaptainHook kullanarak Git hook'ları ile kod kalitesi kontrolünün nasıl yapılacağını gösterir. PHPStan ve Pint araçları ile entegre bir şekilde çalışır.

## Kurulum

### 1. Bağımlılıkları Yükleyin
```bash
composer install
```

### 2. CaptainHook'u Yükleyin
```bash
composer run hooks:install
```

Bu komut, `.git/hooks/` klasöründe gerekli hook dosyalarını oluşturur.

## CaptainHook Yapılandırması

Proje, `captainhook.json` dosyası ile yapılandırılmıştır:

### Pre-Commit Hook'ları
Her commit öncesi çalışır:
- **Pint Kod Formatı Kontrolü**: Laravel Pint ile kod formatting
- **PHPStan Statik Analiz**: Tip kontrolü ve kod kalitesi analizi

### Commit Message Hook'u
Commit mesajlarının belirli formatta olmasını sağlar:
- Format: `type(scope): description`
- Örnek: `feat(user): kullanıcı kayıt özelliği eklendi`
- Geçerli tipler: `feat`, `fix`, `docs`, `style`, `refactor`, `test`, `chore`

## Kullanım Örnekleri

### Yeni Özellik Ekleme
```bash
git add .
git commit -m "feat(auth): kullanıcı giriş sistemi eklendi"
```

### Hata Düzeltme
```bash
git add .
git commit -m "fix(user): email validasyonu düzeltildi"
```

### Kod Refactoring
```bash
git add .
git commit -m "refactor(database): veritabanı bağlantı sınıfı yeniden düzenlendi"
```

## Manuel Araç Kullanımı

### PHPStan Çalıştırma
```bash
composer run phpstan
```

## Hook'ları Geçici Olarak Devre Dışı Bırakma

### Tüm Hook'ları Devre Dışı Bırak
```bash
composer run hooks:disable hook_name
```

### Sadece Bir Commit İçin Atla
```bash
git commit -m "mesaj" --no-verify
```

## Hook'ları Tekrar Aktifleştirme
```bash
composer run hooks:enable hook_name
```

## Özelleştirme

### Commit Mesajı Formatını Değiştirme
`captainhook.json` dosyasındaki `regex` değerini düzenleyin:
```json
{
    "regex": "#^(feat|fix|docs):\\s.{1,50}#",
    "error": "Özel hata mesajınız"
}
```

### Yeni Hook Ekleme
`captainhook.json` dosyasına yeni action'lar ekleyebilirsiniz:
```json
{
    "action": "shell",
    "options": {
        "exec": "php -l {$STAGED_FILES|of-type:php}",
        "fail-on-non-zero": true
    }
}
```

### PHPStan Seviyesini Değiştirme
`phpstan.neon` dosyasındaki `level` değerini (0-9 arası) ayarlayın:
```yaml
parameters:
    level: 6  # Daha az katı için düşük, daha katı için yüksek
```

## Yaygın Problemler ve Çözümleri

### Hook'lar Çalışmıyor
```bash
# Hook dosyalarının var olduğunu kontrol edin
ls -la .git/hooks/

# Yeniden yükleyin
composer run hooks:install
```

### Pint Format Hataları
```bash
# Otomatik düzeltme için
composer run pint

# Manuel kontrol için
composer run pint-check
```

## Faydalı Komutlar

```bash
# Tüm Git hook'larını listele
ls -la .git/hooks/

# CaptainHook sürümünü kontrol et
php vendor/bin/captainhook --version

# Hook yapılandırmasını doğrula
php vendor/bin/captainhook validate

# Hook'ları manuel test et
php vendor/bin/captainhook run pre-commit
```

## İpuçları

1. **İlk Kurulum**: Var olan kodları önce Pint ile formatlayın
2. **Takım Çalışması**: Tüm geliştiricilerin aynı yapılandırmayı kullandığından emin olun
3. **CI/CD Entegrasyonu**: Aynı kontrollerı CI pipeline'ınızda da çalıştırın
4. **Performans**: Büyük projelerde sadece değişen dosyaları kontrol edin

## Daha Fazla Bilgi

- [CaptainHook Dökümantasyonu](https://github.com/captainhookphp/captainhook)
- [PHPStan Kuralları](https://phpstan.org/rules)
- [Laravel Pint](https://laravel.com/docs/pint)
