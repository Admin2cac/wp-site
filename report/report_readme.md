# Thư mục Báo cáo Vận hành & Giám sát Hệ thống WordPress

Thư mục này chứa tài liệu báo cáo kỹ thuật và hướng dẫn quản lý báo cáo cho dự án triển khai và giám sát hệ thống WordPress bằng Docker Compose, Nginx Reverse Proxy, Prometheus, Grafana, Loki và Promtail.

---

## 📁 Cấu trúc thư mục `report/`

```text
report/
├── README.md                                    # Tài liệu hướng dẫn và quản lý thư mục báo cáo (File này)
└── Bao-cao-trien-khai-va-giam-sat-WordPress.md  # Nội dung chi tiết Báo cáo kỹ thuật (Được dùng để biên dịch ra PDF/DOCX)
```

---

## 📋 Danh sách các minh chứng cần đối chiếu trong `screenshots/`

Để báo cáo hoàn chỉnh trước khi nộp, cần đảm bảo thư mục `screenshots/` chứa đủ các ảnh chụp màn hình thực tế tương ứng với các mục kiểm thử trong báo cáo:

1. **`01-docker-ps.png`**: Bảng xuất lệnh `docker compose ps` hoặc `docker ps` thể hiện **10/10 container** đang chạy ở trạng thái `Up` (bao gồm `nginx`, `wordpress`, `db`, `phpmyadmin`, `prometheus`, `node-exporter`, `cadvisor`, `mysql-exporter`, `grafana`, `loki`, `promtail`).
2. **`02-wordpress-home.png`**: Trụ sở trang chủ WordPress truy cập qua Nginx Reverse Proxy (`http://localhost`).
3. **`03-phpmyadmin.png`**: Giao diện đăng nhập/quản trị phpMyAdmin (`http://localhost:8080`).
4. **`04-prometheus-targets.png`**: Trang **Prometheus Targets** (`http://localhost:9090/targets`) thể hiện tất cả các target (`node-exporter`, `cadvisor`, `mysql-exporter`) ở trạng thái **UP (1/1)**.
5. **`05-prometheus-query-up.png`**: Kết quả truy vấn PromQL `up` trên Prometheus UI trả về các giá trị bằng `1`.
6. **`06-grafana-dashboards.png`**: Giao diện Grafana Dashboards hiển thị số liệu thực tế từ Node Exporter, cAdvisor và MySQL Exporter (lưu ý ghi chú panel MySQL bị lỗi `No Data` nếu chưa được khắc phục).
7. **`07-loki-logs-query.png`**: Giao diện **Grafana Explore** thực thi truy vấn LogQL `{service="nginx"}` hoặc `{job="varlogs"}` hiển thị dòng log thu thập được từ Promtail.

---

## 🛠️ Hướng dẫn cập nhật và biên dịch báo cáo

### Chuyển đổi báo cáo Markdown sang Word (.docx)
Nếu cần xuất báo cáo ra định dạng `.docx` để nộp bài, bạn có thể sử dụng giải pháp **Pandoc**:

```bash
# Cú pháp chuyển đổi Pandoc từ Markdown sang DOCX
pandoc report/Bao-cao-trien-khai-va-giam-sat-WordPress.md -o report/Bao-cao-trien-khai-va-giam-sat-WordPress.docx
```

Hoặc bạn có thể mở file `Bao-cao-trien-khai-va-giam-sat-WordPress.md` bằng **VS Code**, sử dụng extension *Markdown Preview Enhanced* hoặc *Docx Exporter* để xuất trực tiếp.

---

## ✍️ Lưu ý dành cho người quản trị báo cáo
- **Không tự bịa số liệu:** Tất cả cấu hình port, tên service, metric và log phải trung thực với file `docker-compose.yml` và các file cấu hình trong `config/`.
- **Bảo mật:** Báo cáo hiện tại ghi nhận hệ thống đang sử dụng giao thức HTTP (chưa cấu hình SSL/TLS HTTPS) và các container đang chạy với quyền mặc định. Cần tuân thủ đúng các hạn chế này khi thuyết minh.