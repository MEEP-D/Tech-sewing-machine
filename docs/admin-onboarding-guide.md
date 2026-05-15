# Hu?ng d?n c?u hình Admin cho ngu?i m?i

Tài li?u này dành cho ngu?i m?i l?n d?u vào trang qu?n tr? d? thi?t l?p d? li?u hi?n th? website.

## 1. Ðang nh?p Admin
1. Truy c?p: `/admin`
2. Ðang nh?p b?ng tài kho?n qu?n tr? du?c c?p.

## 2. Th? t? c?u hình khuy?n ngh?
1. Cài d?t website (`C?u hình website`)
2. Cài d?t SEO (`SEO Settings`)
3. Danh m?c s?n ph?m (`Categories`)
4. Menu (`Menus`)
5. Slider trang ch? (`Sliders`)
6. S?n ph?m (`Products`)
7. Tin t?c (`Posts`)
8. Trang tinh (`Pages`)
9. Ð?i tác/Banner/Section n?u dùng

## 3. C?u hình website (quan tr?ng nh?t)
Vào menu **C?u hình website** và di?n:

### Nh?n di?n thuong hi?u
- `Tiêu d? website`: tên thuong hi?u hi?n th? m?c d?nh cho SEO/title.
- `Mô t? website`: mô t? ng?n v? website/thuong hi?u.
- `Lo?i logo`: ch?n `image` ho?c `text`.
- `Logo sáng`: logo chính dùng ? header frontend.
- `Logo mobile`: có th? d? tr?ng n?u không dùng b?n logo riêng mobile.
- `Favicon`: icon tab trình duy?t (nên dùng ?nh vuông).
- `?nh hero`: ?nh hero m?c d?nh (dùng khi giao di?n có d?c setting này).

### SEO m?c d?nh
- `SEO title m?c d?nh`
- `SEO description m?c d?nh`
- `Canonical m?c d?nh`
- `OG image m?c d?nh`
- `Tên t? ch?c`
- `URL t? ch?c`
- `Robots m?c d?nh` (thu?ng là `index,follow`)
- `SEO description b? sung`

### N?i dung trang Liên h?
- `Dòng ph? (Kicker)`
- `Tiêu d? chính`
- `Mô t? ng?n`

### N?i dung trang S?n ph?m
- `Dòng ph? (Kicker)`
- `Tiêu d? chính`
- `Mô t? ng?n`

Cu?i cùng b?m **Luu c?u hình website**.

## 4. Cài d?t SEO chi ti?t
Vào **SEO Settings**:
- C?p nh?t favicon n?u mu?n qu?n lý riêng qua tab SEO.
- C?p nh?t metadata m?c d?nh cho các trang tinh.
- Luu sau khi ch?nh.

## 5. Danh m?c s?n ph?m
Vào **Categories**:
- T?o danh m?c cha tru?c, danh m?c con sau.
- Ð?t dúng `type=product` cho danh m?c s?n ph?m.
- S?p x?p `sort_order` d? menu/hi?n th? dúng th? t?.

## 6. Menu di?u hu?ng
Vào **Menus**:
- T?o các m?c chính: Trang ch?, Gi?i thi?u, S?n ph?m, Tin t?c, Liên h?.
- G?n dúng URL/route cho t?ng m?c.
- Ki?m tra th? t? hi?n th?.

## 7. Slider trang ch?
Vào **Sliders**:
- Upload ?nh banner ch?t lu?ng cao.
- Nh?p tiêu d?, mô t?, link CTA (n?u có).
- B?t `is_active` và s?p x?p `sort_order`.

## 8. S?n ph?m
Vào **Products**:
- Nh?p tên, slug, mã/sku, giá, mô t? ng?n/dài.
- Gán danh m?c dúng.
- Upload ?nh d?i di?n + gallery (n?u có).
- B?t tr?ng thái publish/active theo nhu c?u.

## 9. Tin t?c
Vào **Posts**:
- Nh?p tiêu d?, slug, n?i dung, ?nh d?i di?n.
- Gán danh m?c tin t?c.
- Thi?t l?p publish date/tr?ng thái xu?t b?n.

## 10. Trang tinh
Vào **Pages**:
- T?o các trang nhu Gi?i thi?u, Chính sách, Ði?u kho?n.
- Ch?n layout phù h?p và di?n SEO riêng cho t?ng trang.

## 11. Ð?i tác / Banner / Section (tu? ch?n)
- **Partners**: thêm logo + link d?i tác.
- **Banners**: thêm banner chi?n d?ch.
- **Sections**: b?t/t?t các kh?i n?i dung trang ch? theo key.

## 12. Checklist ki?m tra sau khi c?u hình
1. Trang ch? có slider, logo, menu, s?n ph?m m?i.
2. Trang s?n ph?m và chi ti?t s?n ph?m m? bình thu?ng.
3. Trang tin t?c và chi ti?t bài vi?t m? bình thu?ng.
4. Favicon hi?n th? trên tab trình duy?t.
5. SEO title/description xu?t hi?n dúng trong source HTML.
6. Form liên h? g?i du?c d? li?u.

## 13. L?i thu?ng g?p
- Upload ?nh xong không th?y: ki?m tra du?ng d?n storage/public và cache trình duy?t.
- S?a n?i dung không d?i ngoài frontend: th? clear cache ?ng d?ng.
- Không th?y danh m?c trong menu: ki?m tra `is_active`, `type`, và `sort_order`.

## 14. G?i ý v?n hành
- Dùng ?nh chu?n kích thu?c, nh?, dúng t? l?.
- Ð?t slug không d?u, ng?n, d? d?c.
- M?i s?n ph?m/bài vi?t nên có SEO title + description riêng.
- Sao luu d? li?u d?nh k? tru?c khi thay d?i l?n.
