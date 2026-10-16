# Đề 3: Website Quảng bá Sản phẩm (WordPress)

Stack: WordPress + MySQL + phpMyAdmin + Nginx reverse proxy + Prometheus + Grafana + Loki/Promtail.

## Chạy
    docker compose config
    docker compose up -d
    docker compose ps

## Địa chỉ
- WordPress: http://localhost
- phpMyAdmin: http://localhost:8081
- Prometheus: http://localhost:9090
- Grafana: http://localhost:3000 (admin / GRAFANA_PASSWORD trong .env)

## Dừng
    docker compose down        # giữ dữ liệu
# Đề 3: Website Quảng bá Sản phẩm – Trà Thái Nguyên

## 1. Giới thiệu

Dự án xây dựng website quảng bá sản phẩm trà Thái Nguyên bằng WordPress, triển khai bằng Docker Compose. Hệ thống tích hợp cơ sở dữ liệu MySQL, Nginx reverse proxy, phpMyAdmin và các công cụ giám sát, tập trung nhật ký.

## 2. Công nghệ sử dụng

- WordPress: quản lý nội dung và website.
- MySQL: lưu trữ dữ liệu.
- phpMyAdmin: quản trị cơ sở dữ liệu.
- Nginx: reverse proxy và cấu hình security headers.
- Prometheus: thu thập metrics.
- Grafana: trực quan hóa dữ liệu giám sát.
- Loki và Promtail: tập trung và truy vấn nhật ký.
- Docker Compose: triển khai các dịch vụ.

## 3. Cấu trúc dự án

- `docker-compose.yml`: cấu hình các container.
- `theme-trathainguyen/`: giao diện WordPress tùy chỉnh.
- `nginx/`: cấu hình Nginx.
- `prometheus/`: cấu hình Prometheus.
- `loki/`: cấu hình Loki.
- `promtail/`: cấu hình Promtail.
- `mysql-init/`: các tệp khởi tạo MySQL nếu được cấu hình.
- `screenshots/`: ảnh minh chứng.
- `report/`: báo cáo dự án.

## 4. Yêu cầu môi trường

- Docker Desktop đã cài đặt và đang chạy.
- Docker Compose.
- Git (nếu lấy mã nguồn từ GitHub).

## 5. Cài đặt và chạy

### Bước 1: Lấy mã nguồn

```bash
git clone https://github.com/Admin2cac/wp-site.git
cd wp-site
```

### Bước 2: Cấu hình biến môi trường

Tạo tệp `.env` tại thư mục gốc dựa trên các biến mà `docker-compose.yml` yêu cầu.

Không sử dụng mật khẩu ví dụ trong môi trường thực tế. Không đưa tệp `.env` chứa thông tin bí mật lên GitHub.

### Bước 3: Kiểm tra cấu hình

```bash
docker compose config
```

### Bước 4: Khởi động hệ thống

```bash
docker compose up -d
docker compose ps
```

### Bước 5: Kiểm tra nhật ký nếu có lỗi

```bash
docker compose logs --tail=100
```

## 6. Địa chỉ truy cập

- Website WordPress: http://localhost
- phpMyAdmin: http://localhost:8081
- Prometheus: http://localhost:9090
- Grafana: http://localhost:3000

Tài khoản Grafana và các tài khoản cơ sở dữ liệu sử dụng giá trị được cấu hình trong `.env`. Không công khai mật khẩu trong tài liệu hoặc repository.

## 7. Giám sát hệ thống

### Prometheus

Truy cập giao diện Prometheus, mở mục **Status → Targets** để kiểm tra trạng thái các targets.

### Grafana

Đăng nhập Grafana, kiểm tra các data source và dashboard đã cấu hình. Dashboard phụ thuộc vào exporter và nguồn dữ liệu thực tế của dự án.

### Loki và Promtail

Sử dụng Grafana Explore để truy vấn nhật ký được Promtail thu thập và gửi tới Loki.

## 8. Nginx và bảo mật

Nginx được sử dụng làm reverse proxy phía trước WordPress. Cấu hình hiện tại có các security headers như:

- `X-Frame-Options`
- `X-Content-Type-Options`
- `Referrer-Policy`

Kiểm tra cấu hình Nginx:

```bash
docker compose exec nginx nginx -t
```

Các security headers không thay thế HTTPS. Nếu triển khai công khai, cần cấu hình HTTPS, bảo vệ thông tin đăng nhập và rà soát các cổng dịch vụ được phép truy cập.

## 9. Dừng hệ thống

```bash
docker compose down
```

Lệnh này dừng và xóa các container của dự án nhưng thông thường giữ lại dữ liệu trong named volumes. Không sử dụng `-v` nếu chưa muốn xóa dữ liệu.

## 10. Ảnh minh chứng và báo cáo

Thư mục `screenshots/` lưu ảnh website, trạng thái container, Prometheus targets, dashboard Grafana, truy vấn Loki và cấu hình Nginx.

Thư mục `report/` lưu báo cáo triển khai và quản trị hệ thống.

## 11. Repository

GitHub: https://github.com/Admin2cac/wp-site