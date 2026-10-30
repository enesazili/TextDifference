# Text Diff

İki metni satır satır karşılaştıran, tek dosyalık tarayıcı aracı. Eklenen, silinen ve
değişen satırları numaralandırarak gösterir; sonucu birleşik (unified) metin olarak da
üretebilir. Metinler sunucuya gönderilmez.

- Dosya: `textdiff.php` (tek dosya, ~46 KB)
- Çalıştığı yer: tarayıcı (istemci tarafı)
- Sunucu tarafı kod: yok

## Amaç

Sürüm kontrolü olmayan iki metin sürümünü (yapılandırma, metin, not, CSV çıktısı) hızlıca
karşılaştırmak ve farkları satır numarasıyla görmek.

## Özellikler

- Satır bazlı karşılaştırma; `equal`, `added`, `removed`, `change` etiketleri.
- Her iki tarafta satır numarası; sayılan ekleme/silme/değişiklik özeti.
- CRLF, LF ve tek başına CR satır sonları eşdeğer kabul edilir.
- Unicode (Türkçe karakterler) korunur.
- Birleşik (unified) metin çıktısı (`--- eski`, `+++ yeni`, `+`/`-`/boşluk önekleri).
- Boş alan davranışı: iki alan da boşsa karşılaştırma yapılmaz; yalnızca biri doluysa
  tüm satırlar eklenen veya silinen olarak gösterilir.
- Yapıştırma, klavye ile gezinme ve görünür odak desteği; açık/koyu tema.

## Gereksinimler

- Güncel Chrome, Edge, Firefox veya Safari.
- JavaScript açık olmalıdır.
- Ağ bağlantısı gerekmez; CDN, harici font veya uzak kütüphane yoktur.

## Çalıştırma

```powershell
python -m http.server 8000
# veya
php -S 127.0.0.1:8000
```

`http://127.0.0.1:8000/textdiff.php` adresini açın. `file://` üzerinden de çalışır.

## Kullanım

1. Sol alana eski metni, sağ alana yeni metni yapıştırın.
2. "Karşılaştır" düğmesine basın.
3. Sonuç tablosunda satır numaralarına ve etiketlere bakın; isterseniz birleşik metni
   kopyalayın.
4. Girdileri temizlemek için sayfayı yenilemeniz yeterlidir; kalıcı kayıt yoktur.

## Teknik ayrıntılar

- Fark hesabı, satır dizileri üzerinde LCS (en uzun ortak alt dizi) tabanlı bir yürüyüşle
  yapılır; sonuç `equal` / `added` / `removed` / `change` satırlarına dönüştürülür.
- Yeni satırlar `\r\n`, `\r` ve `\n` biçimlerinden normalize edilir; karşılaştırma
  satır sonu biçiminden etkilenmez.
- Sonuç tablosu kendi içinde yatay kaydırılır; sayfa gövdesi taşmaz.
- Saf mantık (`normalizeNewlines`, `splitLines`, `diffLines`, `toUnifiedText`) arayüzden
  bağımsızdır ve doğrudan test edilebilir.
- Kullanıcı metni DOM'a `textContent` ile yazılır; HTML olarak yorumlanmaz.

## Sınırlar ve güvenlik

| Sınır | Değer |
| --- | --- |
| Satır sayısı (her metin) | 1500 |
| Karşılaştırma hücresi | 4.000.000 |

- Sınır aşılırsa işlem yapılmaz ve neden bildirilir; tarayıcı kilitlenmesi engellenir.
- Ağ isteği yoktur; form gönderimi, çerez, oturum veya analitik kullanılmaz.
- Metinler sayfa ömrü boyunca yalnızca bellekte tutulur.
- Çok uzun satırlarda tablo hücresi kendi içinde kayar; satır kırılması yapılmaz.

## Test edilenler

2026-10-28, Edge/Chromium 156 ve PHP 8.3.36 ile:

- Dosya portfolyo dışındaki geçici bir klasörden servis edildi: `HTTP 200`, tek `<h1>`,
  harici kaynak yok, konsol hatası yok.
- `diffLines` ekleme/silme/değişiklik sayılarını doğru üretti (2 satırlık örnekte
  1 ekleme + 1 silme).
- Birleşik metin başlıkları (`--- eski`, `+++ yeni`) ve satır önekleri doğru.
- CRLF normalizasyonu sonrası `\r` kalmadı.
- Arayüz üzerinden iki gerçek metin karşılaştırıldı; tabloda 4 satır oluştu.
- 1440 px ve 390 px genişlikte sayfa taşması yok.

## Test edilmeyenler ve bilinen sınırlar

- Firefox ve Safari'de gerçek tarayıcı testi yapılmadı.
- Gerçek mobil cihaz ve ekran okuyucu testi yapılmadı.
- 1500 satırlık üst sınırda performans ve bellek davranışı ölçülmedi.
- Kelime düzeyinde (satır içi) fark yoktur; yalnızca satır bazlı karşılaştırma yapılır.
- Boşluk/girinti farkları ayrı bir seçenekle yok sayılmaz.
