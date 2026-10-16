<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<footer class="site-footer" id="lien-he">
    <div class="footer-inner">
        <div>
            <h2>🍃 Trà Thái Nguyên</h2>
            <p>Đậm đà hương vị trà Việt, kết tinh từ vùng đất chè Thái Nguyên.</p>
        </div>

        <div>
            <h3>Khám phá</h3>
            <a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a>
            <a href="<?php echo esc_url(home_url('/#san-pham')); ?>">Sản phẩm</a>
        </div>

        <div>
            <h3>Liên hệ</h3>
            <p>Thái Nguyên, Việt Nam</p>
            <p>Website quảng bá đặc sản trà địa phương</p>
        </div>
    </div>

    <div class="footer-bottom">
        © <?php echo esc_html(wp_date('Y')); ?> Trà Thái Nguyên
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>