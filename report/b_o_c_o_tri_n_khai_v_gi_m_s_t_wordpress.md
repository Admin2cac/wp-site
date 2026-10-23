# TRƯỜNG ĐẠI HỌC / HỌC VIỆN
### KHOA CÔNG NGHỆ THÔNG TIN

---

\
\
\

# BÁO CÁO THỰC HÀNH / BÀI TẬP LỚN

## BÁO CÁO TRIỂN KHAI VÀ GIÁM SÁT HỆ THỐNG WORDPRESS VỚI DOCKER, PROMETHEUS, GRAFANA VÀ LOKI

\
\

* **Họ và tên sinh viên / Người thực hiện:** Admin2cac
* **Dự án (Repository):** `https://github.com/Admin2cac/wp-site`
* **Thời gian thực hiện:** Tháng 10 / 2026
* **Trạng thái hệ thống:** Đã triển khai thành công 10 dịch vụ container

\
\
\
\

---
*Năm 2026*

\newpage

## MỤC LỤC

1. GIỚI THIỆU VÀ MỤC TIÊU DỰ ÁN
   1.1. Đặt vấn đề
   1.2. Mục tiêu dự án
2. KIẾN TRÚC HỆ THỐNG VÀ PHÂN TÍCH CHỨC NĂNG DỊCH VỤ
   2.1. Sơ đồ tổng quan kiến trúc hệ thống
   2.2. Chi tiết 10 dịch vụ Docker
3. HƯỚNG DẪN TRIỂN KHAI VÀ VẬN HÀNH BẰNG DOCKER COMPOSE
   3.1. Yêu cầu môi trường
   3.2. Quy trình triển khai từng bước
   3.3. Các lệnh quản lý và vận hành hệ thống
4. PHÂN TÍCH CẤU HÌNH DỊCH VỤ VÀ REVERSE PROXY NGINX
   4.1. Cấu hình Reverse Proxy (`nginx.conf`)
   4.2. Cấu hình thu thập Metrics (Prometheus)
   4.3. Cấu hình thu thập Logs (Loki & Promtail)
5. KẾT QUẢ KIỂM THỬ VÀ GIÁM SÁT HỆ THỐNG
   5.1. Trạng thái hoạt động của các Container
   5.2. Kết quả thu thập Metrics trên Prometheus
   5.3. Kết quả hiển thị Trực quan hóa trên Grafana
   5.4. Kết quả thu thập Log tập trung với Loki & Promtail
6. PHÂN TÍCH BỆNH PHÁP BẢO MẬT ĐÃ TRIỂN KHAI
7. HẠN CHẾ, VẤN ĐỀ TỒN TẠI VÀ HƯỚNG KHẮC PHỤC
   7.1. Các hạn chế và sự cố ghi nhận
   7.2. Hướng khắc phục và phát triển
8. KẾT LUẬN
9. DANH SÁCH ẢNH MINH CHỨNG (DỐI CHIẾU THƯ MỤC SCREENSHOTS/)

---

\newpage

## 1. GIỚI THIỆU VÀ MỤC TIÊU DỰ ÁN

### 1.1. Đặt vấn đề
Trong kỷ nguyên phát triển ứng dụng web hiện đại, việc đảm bảo tính sẵn sàng (High Availability), khả năng mở rộng (Scalability) và tính minh bạch trong vận hành (Observability) là yếu tố then chốt. WordPress là một trong những Hệ quản trị nội dung (CMS) phổ biến nhất thế giới. Tuy nhiên, việc triển khai WordPress theo phương thức truyền thống trên máy chủ vật lý hoặc VM đơn lẻ thường gặp nhiều khó khăn trong quản lý phụ thuộc, mở rộng và giám sát hiệu năng.

Để giải quyết bài toán này, dự án hướng tới giải pháp Container hóa toàn bộ hệ thống ứng dụng cùng với một hạ tầng giám sát toàn diện (Full-stack Observability System) bao gồm Metrics và Logs tập trung.

### 1.2. Mục tiêu dự án
* **Container hóa:** Đóng gói ứng dụng WordPress, cơ sở dữ liệu MySQL, công cụ quản trị phpMyAdmin và Nginx Reverse Proxy bằng Docker và Docker Compose.
* **Tự động điều hướng Traffic:** Sử dụng Nginx làm Reverse Proxy đóng vai trò Cổng vào (Gateway) duy nhất tiếp nhận request từ người dùng.
* **Giám sát tài nguyên & Metric (Monitoring):** Triển khai Prometheus cùng các Exporters (Node Exporter, cAdvisor, MySQL Exporter) để thu thập thông số phần cứng, hạ tầng container và cơ sở dữ liệu.
* **Quản lý Log tập trung (Logging):** Triển khai Grafana Loki và Promtail để gom toàn bộ log từ Nginx và hệ thống về một điểm duy nhất.
* **Trực quan hóa (Visualization):** Sử dụng Grafana xây dựng Dashboards theo dõi toàn bộ trạng thái hoạt động của hệ thống theo thời gian thực.

---

## 2. KIẾN TRÚC HỆ THỐNG VÀ PHÂN TÍCH CHỨC NĂNG DỊCH VỤ

### 2.1. Sơ đồ tổng quan kiến trúc hệ thống

Hệ thống bao gồm 10 dịch vụ chạy trong các container độc lập, tương tác qua mạng nội bộ Docker (`wp-network`):

```text
                                +-------------------+
                                |    User / Client  |
                                +---------+---------+
                                          | HTTP (:80)
                                          v
                                +-------------------+
                                |   Nginx Proxy     |
                                +----+----------+---+
                                     |          |
                   +-----------------+          +-----------------+
                   | :8080                                        | :80
                   v                                              v
         +------------------+                           +------------------+
         |    phpMyAdmin    |                           |    WordPress     |
         +--------+---------+                           +--------+---------+
                  |                                              |
                  +-----------------------+----------------------+
                                          |
                                          v
                                +-------------------+
                                |   MySQL Database  |
                                +-------------------+

========================== HẠ TẦNG GIÁM SÁT (OBSERVABILITY) ==========================

 [ Node Exporter ] ---> Thu thập OS Metrics  -----\
 [ cAdvisor      ] ---> Thu thập Container   ------> [ Prometheus ] ---> [ Grafana ]
 [ MySQL Exporter] ---> Thu thập Database    -----/                     (:3000)
                                                                            ^
 [ Nginx / System] ---> [ Promtail ] ---------------> [ Loki ] -------------/
```

### 2.2. Chi tiết 10 dịch vụ Docker

1. **`nginx`**: Reverse Proxy tiếp nhận lưu lượng truy cập HTTP port `80`, điều hướng sang WordPress (`wordpress:80`) hoặc phpMyAdmin (`phpmyadmin:80`).
2. **`wordpress`**: Chạy ứng dụng WordPress chính trên PHP-FPM/Apache, xử lý logic nghiệp vụ web.
3. **`db` (MySQL 8.0)**: Hệ quản trị cơ sở dữ liệu lưu trữ toàn bộ bài viết, cấu hình, thông tin người dùng của WordPress.
4. **`phpmyadmin`**: Giao diện web quản trị CSDL MySQL, lắng nghe tại port `8080` (qua Nginx).
5. **`prometheus`**: Máy chủ giám sát thu thập (pull) metrics từ các Exporter định kỳ và lưu trữ dưới dạng Time-Series Database.
6. **`node-exporter`**: Thu thập chỉ số tài nguyên máy chủ (CPU, RAM, Disk, Network) của Host OS.
7. **`cadvisor`**: Công cụ của Google thu thập chỉ số hiệu năng thực thi của từng Docker Container.
8. **`mysql-exporter`**: Kết nối vào MySQL để thu thập các metrics về query performance, connection status, buffer pool.
9. **`loki`**: Hệ thống quản lý và lưu trữ log tập trung (Log Aggregation Engine) tối ưu dung lượng.
10. **`promtail`**: Agent thu thập các file log trên máy chủ và container, thực hiện parse và đẩy (push) về Loki.

---

## 3. HƯỚNG DẪN TRIỂN KHAI VÀ VẬN HÀNH BẰNG DOCKER COMPOSE

### 3.1. Yêu cầu môi trường
* Hệ điều hành: Linux (Ubuntu 20.04/22.04 LTS) hoặc macOS/Windows có cài Docker Desktop.
* Docker Engine version 20.10+
* Docker Compose version v2.0+
* Ram tối thiểu: 4GB RAM (Khuyến nghị 8GB để chạy mượt cả 10 container).

### 3.2. Quy trình triển khai từng bước

**Bước 1:** Clone dự án từ GitHub về máy chủ:
```bash
git clone https://github.com/Admin2cac/wp-site.git
cd wp-site
```

**Bước 2:** Kiểm tra cấu hình file `docker-compose.yml` và các thư mục cấu hình `config/`.

**Bước 3:** Khởi chạy toàn bộ hệ thống ở chế độ background (detached mode):
```bash
docker compose up -d
```

**Bước 4:** Kiểm tra danh sách các dịch vụ đang chạy:
```bash
docker compose ps
```

### 3.3. Các lệnh quản lý và vận hành hệ thống

* **Xem log của một dịch vụ cụ thể (VD: Nginx):**
  ```bash
  docker compose logs -f nginx
  ```
* **Dừng toàn bộ hệ thống:**
  ```bash
  docker compose stop
  ```
* **Khởi động lại toàn bộ hệ thống:**
  ```bash
  docker compose restart
  ```
* **Gỡ bỏ hệ thống và giữ nguyên dữ liệu (Volumes):**
  ```bash
  docker compose down
  ```

---

## 4. PHÂN TÍCH CẤU HÌNH DỊCH VỤ VÀ REVERSE PROXY NGINX

### 4.1. Cấu hình Reverse Proxy (`config/nginx/nginx.conf`)

Nginx đóng vai trò là điểm tiếp nhận yêu cầu tập trung. Cấu hình điều hướng dựa trên Location Block:

```nginx
server {
    listen 80;
    server_name localhost;

    # Điều hướng trang chủ và các yêu cầu chung tới WordPress
    location / {
        proxy_pass http://wordpress:80;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }

    # Điều hướng quản trị CSDL tới phpMyAdmin
    location /phpmyadmin/ {
        proxy_pass http://phpmyadmin:80/;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

### 4.2. Cấu hình thu thập Metrics (`config/prometheus/prometheus.yml`)

Prometheus được thiết lập để quét (scrape) các endpoint theo chu kỳ 15 giây:

```yaml
global:
  scrape_interval: 15s

scrape_configs:
  - job_name: 'node-exporter'
    static_configs:
      - targets: ['node-exporter:9100']

  - job_name: 'cadvisor'
    static_configs:
      - targets: ['cadvisor:8080']

  - job_name: 'mysql'
    static_configs:
      - targets: ['mysql-exporter:9104']
```

### 4.3. Cấu hình thu thập Logs (`config/promtail/promtail-config.yml`)

Promtail được cấu hình để đọc file log từ hệ thống `/var/log` và đẩy dữ liệu về Loki qua giao thức HTTP:

```yaml
server:
  http_listen_port: 9080
  grpc_listen_port: 0

positions:
  filename: /tmp/positions.yaml

clients:
  - url: http://loki:3100/loki/api/v1/push

scrape_configs:
  - job_name: system
    static_configs:
      - targets:
          - localhost
        labels:
          job: varlogs
          __path__: /var/log/*log
```

---

## 5. KẾT QUẢ KIỂM THỬ VÀ GIÁM SÁT HỆ THỐNG

### 5.1. Trạng thái hoạt động của các Container
Thực hiện chạy lệnh `docker compose ps` trên môi trường triển khai thực tế. Kết quả ghi nhận **10/10 dịch vụ** đều ở trạng thái `Up` (Hoạt động bình thường):

* `wp-site-nginx-1`: Up (Ports 0.0.0.0:80->80/tcp, 0.0.0.0:8080->80/tcp)
* `wp-site-wordpress-1`: Up (Port 80/tcp)
* `wp-site-db-1`: Up (Port 3306/tcp)
* `wp-site-phpmyadmin-1`: Up (Port 80/tcp)
* `wp-site-prometheus-1`: Up (Port 0.0.0.0:9090->9090/tcp)
* `wp-site-node-exporter-1`: Up (Port 9100/tcp)
* `wp-site-cadvisor-1`: Up (Port 8080/tcp)
* `wp-site-mysql-exporter-1`: Up (Port 9104/tcp)
* `wp-site-grafana-1`: Up (Port 0.0.0.0:3000->3000/tcp)
* `wp-site-loki-1`: Up (Port 3100/tcp)
* `wp-site-promtail-1`: Up

### 5.2. Kết quả thu thập Metrics trên Prometheus
1. **Kiểm tra Targets (`http://localhost:9090/targets`):**
   * Target `node-exporter`: State **UP**
   * Target `cadvisor`: State **UP**
   * Target `mysql`: State **UP**
2. **Truy vấn PromQL:**
   * Thực hiện truy vấn `up` trên giao diện Console của Prometheus.
   * Kết quả trả về 3 chuỗi dữ liệu (time series) đại diện cho 3 exporters, cả 3 đều có giá trị bằng `1` (biểu thị dịch vụ hoạt động khỏe mạnh).

### 5.3. Kết quả hiển thị Trực quan hóa trên Grafana
* Đã kết nối thành công Data Source Prometheus (`http://prometheus:9090`) và Loki (`http://loki:3100`).
* **Node Exporter Dashboard:** Hiển thị trực quan biểu đồ CPU Usage, Memory Utilization, Disk I/O theo thời gian thực.
* **cAdvisor Dashboard:** Hiển thị chi tiết mức tiêu thụ tài nguyên CPU/RAM của từng container (`wordpress`, `db`, `nginx`).
* **MySQL Dashboard:** *Ghi nhận sự cố:* Một số Panel liên quan đến MySQL Status hiển thị trạng thái `No data`. Nguyên nhân do quyền hạn user `exporter` trong MySQL chưa đủ để truy cập bảng `performance_schema` hoặc thiếu thông số biến môi trường kết nối.

### 5.4. Kết quả thu thập Log tập trung với Loki & Promtail
* Vào mục **Grafana Explore**, chọn Data Source **Loki**.
* Thực thi truy vấn LogQL: `{job="varlogs"}` hoặc `{service="nginx"}`.
* **Kết quả:** Hệ thống hiển thị dòng log thời gian thực thu thập được từ Promtail, cho phép lọc log theo cấp độ (INFO, WARN, ERROR) và tìm kiếm chuỗi văn bản.

---

## 6. PHÂN TÍCH BỆNH PHÁP BẢO MẬT ĐÃ TRIỂN KHAI

Hệ thống bước đầu triển khai các biện pháp bảo mật cơ bản mức hạ tầng container:

1. **Cô lập mạng nội bộ (Network Isolation):** Chỉ công khai các cổng cần thiết ra bên ngoài Host (`80` cho Nginx, `3000` cho Grafana, `9090` cho Prometheus). Các container ứng dụng gốc (`wordpress`, `db`) không mở port trực tiếp ra Internet mà giao tiếp nội bộ qua bridge network của Docker.
2. **Ẩn thông tin quản trị:** Nginx đóng vai trò lớp đệm phân tách client với các dịch vụ backend, giúp giấu IP nội bộ của ứng dụng.
3. **Quản lý mật khẩu qua biến môi trường:** Mật khẩu MySQL Root và Database User được truyền qua các biến môi trường trong `docker-compose.yml` thay vì mã hóa cứng trong code ứng dụng.

---

## 7. HẠN CHẾ, VẤN ĐỀ TỒN TẠI VÀ HƯỚNG KHẮC PHỤC

### 7.1. Các hạn chế và sự cố ghi nhận

1. **Chưa triển khai HTTPS (SSL/TLS):**
   * *Hạn chế:* Toàn bộ lưu lượng mạng giữa Client và Nginx Reverse Proxy vẫn chạy trên giao thức HTTP mã rõ (Port 80).
   * *Nguy cơ:* Dữ liệu đăng nhập và thông tin cá nhân có thể bị chặn bắt (Sniffing) trên đường truyền.
2. **Container chạy với quyền Root (Root User execution):**
   * *Hạn chế:* Mặc định một số container (như Nginx, WordPress) chạy dưới quyền Root bên trong container.
   * *Nguy cơ:* Nếu ứng dụng bị khai thác lỗ hổng RCE, kẻ tấn công có nguy cơ chiếm quyền trên máy chủ Host (Container Escape).
3. **Sự cố Panel MySQL Exporter hiển thị `No data`:**
   * *Nguyên nhân:* Mật khẩu hoặc quyền hạn của tài khoản kết nối MySQL Exporter chưa được cấp đủ quyền `PROCESS, REPLICATION CLIENT, SELECT` trên MySQL.

### 7.2. Hướng khắc phục và phát triển

* **Khắc phục sự cố MySQL Exporter:** Cập nhật lại câu lệnh SQL cấp quyền cho user `exporter` trong MySQL:
  ```sql
  GRANT PROCESS, REPLICATION CLIENT, SELECT ON *.* TO 'exporter'@'%';
  FLUSH PRIVILEGES;
  ```
* **Tích hợp SSL/TLS Free (Let's Encrypt / Certbot):** Cấu hình thêm Nginx để hỗ trợ HTTPS port 433 và tự động gia hạn chứng chỉ số.
* **Cấu hình Non-root User cho Dockerfile:** Bổ sung chỉ thị `USER node` hoặc tạo user không có quyền root trong Dockerfile custom.
* **Cấu hình Alertmanager:** Tích hợp Alertmanager vào Prometheus để gửi cảnh báo tự động qua Telegram/Discord khi có dịch vụ bị Down hoặc CPU quá tải.

---

## 8. KẾT LUẬN

Dự án đã triển khai thành công hệ thống ứng dụng WordPress hoàn chỉnh dựa trên công nghệ Docker và Docker Compose. Kết hợp với đó là giải pháp giám sát tập trung hiện đại bao gồm Prometheus, Grafana, Loki và Promtail.

Hệ thống đạt được các mục tiêu đề ra về tính đóng gói, khả năng vận hành dễ dàng, theo dõi chỉ số tài nguyên và log theo thời gian thực. Mặc dù vẫn còn một số hạn chế về mặt bảo mật HTTPS và lỗi kết nối dữ liệu MySQL Exporter cục bộ, dự án đã cung cấp một nền tảng vững chắc và sẵn sàng cho việc nâng cấp, hoàn thiện trong tương lai.

---

## 9. DANH SÁCH ẢNH MINH CHỨNG (ĐỐI CHIẾU THƯ MỤC SCREENSHOTS/)

*(Chú thích: Xem chi tiết các ảnh minh chứng thực tế trong thư mục `screenshots/` của dự án)*

1. **Hình 1 (`screenshots/01-docker-ps.png`):** Bảng trạng thái `docker compose ps` hiển thị 10/10 container ở trạng thái `Up`.
2. **Hình 2 (`screenshots/02-wordpress-home.png`):** Giao diện trang chủ WordPress truy cập thành công qua HTTP Port 80.
3. **Hình 3 (`screenshots/03-phpmyadmin.png`):** Trang quản trị cơ sở dữ liệu phpMyAdmin qua Nginx.
4. **Hình 4 (`screenshots/04-prometheus-targets.png`):** Màn hình Prometheus Targets xác nhận 3 Exporters ở trạng thái `UP`.
5. **Hình 5 (`screenshots/05-prometheus-query-up.png`):** Màn hình PromQL Query với câu lệnh `up` trả về kết quả bằng `1`.
6. **Hình 6 (`screenshots/06-grafana-dashboards.png`):** Dashboard Grafana hiển thị trực quan hóa Metrics (và minh chứng Panel MySQL `No Data`).
7. **Hình 7 (`screenshots/07-loki-logs-query.png`):** Màn hình Grafana Explore truy vấn LogQL `{job="varlogs"}` hiển thị log thu thập từ Loki.