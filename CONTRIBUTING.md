# Contributing to Network Tools

Terima kasih atas minat Anda untuk berkontribusi pada Network Tools! 🎉

## 🚀 Cara Berkontribusi

### 1. Fork Repository
- Fork repository ini ke akun GitHub Anda
- Clone fork ke komputer lokal:
```bash
git clone https://github.com/YOUR_USERNAME/network-tools.git
cd network-tools
```

### 2. Setup Development Environment
- Pastikan PHP 8.0+ terinstall
- Setup web server lokal (Apache/Nginx/XAMPP)
- Test semua tools berfungsi dengan baik

### 3. Buat Branch Baru
```bash
git checkout -b feature/nama-fitur-baru
# atau
git checkout -b fix/nama-bug-yang-diperbaiki
```

### 4. Lakukan Perubahan
- Ikuti coding standards yang ada
- Tambahkan comments untuk logic yang kompleks
- Test semua perubahan secara menyeluruh

### 5. Commit & Push
```bash
git add .
git commit -m "feat: tambah fitur X" # atau "fix: perbaiki bug Y"
git push origin feature/nama-fitur-baru
```

### 6. Buat Pull Request
- Buat PR dari branch Anda ke branch `main`
- Berikan deskripsi yang jelas tentang perubahan
- Lampirkan screenshot jika perubahan UI

## 📋 Guidelines

### Coding Standards
- Gunakan PHP PSR-12 coding standards
- Indentasi menggunakan 4 spasi
- Gunakan camelCase untuk variabel JavaScript
- Gunakan snake_case untuk variabel PHP

### Commit Messages
Gunakan format commit yang jelas:
- `feat:` untuk fitur baru
- `fix:` untuk bug fixes
- `docs:` untuk perubahan dokumentasi
- `style:` untuk perubahan formatting
- `refactor:` untuk refactoring code
- `test:` untuk menambah tests

### Menambah Tool Baru

#### 1. Update UI (index.php)
Tambahkan option baru di dropdown:
```html
<option value="tool-baru">Tool Baru - Deskripsi</option>
```

#### 2. Update API (api.php)
Tambahkan case baru:
```php
case 'tool-baru':
    $domain = cleanDomain($query);
    // Logic untuk tool baru
    $result = [
        ['label' => 'Label', 'value' => 'Value']
    ];
    respond(true, $result, '', "Title untuk {$domain}");
    break;
```

#### 3. Update Documentation
- Tambahkan tool baru ke README.md
- Update tabel API endpoints
- Tambahkan contoh usage jika diperlukan

### Testing

Sebelum submit PR, pastikan:
- [ ] Semua tools yang ada masih berfungsi
- [ ] Tool baru berfungsi dengan berbagai input
- [ ] Error handling bekerja dengan baik
- [ ] UI responsive di mobile dan desktop
- [ ] Tidak ada console errors

### Code Review Process

1. **Automated Checks**: Code akan dicek otomatis
2. **Manual Review**: Maintainer akan review code
3. **Testing**: Perubahan akan ditest
4. **Merge**: Jika semua OK, PR akan di-merge

## 🐛 Melaporkan Bug

### Sebelum Melaporkan
- Cek apakah bug sudah dilaporkan di [Issues](https://github.com/fianbiasa/network-tools/issues)
- Pastikan menggunakan versi terbaru

### Format Laporan Bug
```markdown
## Bug Description
Deskripsi singkat tentang bug

## Steps to Reproduce
1. Buka halaman X
2. Klik tombol Y
3. Input Z
4. Error muncul

## Expected Behavior
Yang seharusnya terjadi

## Actual Behavior
Yang sebenarnya terjadi

## Environment
- Browser: Chrome 118
- OS: Windows 11
- PHP Version: 8.1
- Server: Apache 2.4

## Screenshots
(jika ada)
```

## 💡 Ideas & Feature Requests

Punya ide untuk fitur baru? Buat issue dengan label `enhancement`:

### Format Feature Request
```markdown
## Feature Description
Deskripsi fitur yang diinginkan

## Use Case
Kapan dan mengapa fitur ini berguna

## Proposed Solution
Bagaimana fitur ini bisa diimplementasi

## Alternatives
Alternatif lain yang sudah dipertimbangkan
```

## 🎯 Priority Areas

Kami sedang fokus pada area berikut:

### High Priority
- Performance optimization
- Mobile UX improvements
- Additional DNS tools
- Rate limiting implementation

### Medium Priority
- Dark mode
- Export results feature
- Bulk checking
- API authentication

### Low Priority
- Internationalization
- Advanced analytics
- Custom themes

## 📞 Komunikasi

- **GitHub Issues**: Untuk bug reports dan feature requests
- **GitHub Discussions**: Untuk diskusi umum
- **Email**: Untuk hal yang sensitif atau private

## 🙏 Recognition

Kontributor akan:
- Dicantumkan di README.md
- Mendapat credit di changelog
- Diundang sebagai collaborator (untuk kontributor aktif)

## 📚 Resources

### Documentation
- [PHP Manual](https://www.php.net/manual/)
- [MDN Web Docs](https://developer.mozilla.org/)
- [DNS Record Types](https://en.wikipedia.org/wiki/List_of_DNS_record_types)

### Tools
- [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
- [ESLint](https://eslint.org/)
- [Postman](https://www.postman.com/) untuk testing API

## ❤️ Thank You

Setiap kontribusi, sekecil apapun, sangat berharga untuk project ini. Terima kasih sudah menjadi bagian dari komunitas Network Tools!

---

Happy coding! 🚀