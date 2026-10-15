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
