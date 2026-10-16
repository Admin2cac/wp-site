
<?php
if (!defined('ABSPATH')) {
    exit;
}
get_header();
?>

<main>
    <section class="hero">
        <div class="hero-content">
            <span class="hero-label">ĐẶC SẢN TRÀ VIỆT NAM</span>
            <h1>Trà Thái Nguyên<br><em>Tinh hoa đất chè</em></h1>
            <p>
                Khám phá hương vị trà xanh truyền thống từ vùng đất
                nổi tiếng với những đồi chè xanh mướt và văn hóa trà lâu đời.
            </p>
            <a class="button-primary" href="#san-pham">Khám phá sản phẩm</a>
        </div>
        <div class="hero-decoration" aria-hidden="true">
            <span>🍃</span>
            <span>TRÀ XANH · HƯƠNG VIỆT</span>
        </div>
    </section>

    <section class="intro section-wrap" id="gioi-thieu">
        <div class="intro-mark">
            <img
                src="<?php echo esc_url(get_theme_file_uri('assets/images/bup-che.jpg')); ?>"
                alt="Những búp chè xanh Thái Nguyên"
                loading="lazy"
            >
        </div>
        <div class="intro-content">
            <span class="eyebrow">CÂU CHUYỆN VÙNG TRÀ</span>
            <h2>Hương trà từ miền đất xanh</h2>
            <p>
                Thái Nguyên được biết đến rộng rãi với truyền thống trồng
                và chế biến chè. Những búp chè được chăm sóc, thu hái và
                chế biến thành thức uống mang hương vị đặc trưng của trà Việt.
            </p>
            <p>
                Website giới thiệu nguồn gốc, nét văn hóa và các dòng trà
                tiêu biểu, giúp người yêu trà tìm hiểu thêm về đặc sản địa phương.
            </p>
        </div>
    </section>

    <section class="products section-wrap" id="san-pham">
        <div class="section-heading">
            <span class="eyebrow">KHÁM PHÁ HƯƠNG VỊ</span>
            <h2>Các dòng trà tiêu biểu</h2>
            <p>Tìm hiểu những sản phẩm trà quen thuộc của vùng chè Thái Nguyên.</p>
        </div>

        <div class="product-grid">
            <article class="product-card">
                <div class="product-art">
                    <img src="<?php echo esc_url(get_theme_file_uri('assets/images/tra-thanh-pham.jpg')); ?>" alt="Trà xanh Thái Nguyên" loading="lazy">
                </div>
                <div class="product-info">
                    <span class="product-tag">TRÀ TRUYỀN THỐNG</span>
                    <h3>Trà xanh Thái Nguyên</h3>
                    <p>Dòng trà xanh quen thuộc, nổi bật với hương thơm tự nhiên.</p>
                </div>
            </article>

            <article class="product-card">
                <div class="product-art">
                    <img src="<?php echo esc_url(get_theme_file_uri('assets/images/tra-thanh-pham.jpg')); ?>" alt="Trà Tân Cương" loading="lazy">
                </div>
                <div class="product-info">
                    <span class="product-tag">TUYỂN CHỌN</span>
                    <h3>Trà Tân Cương</h3>
                    <p>Giới thiệu vùng chè Tân Cương nổi tiếng của Thái Nguyên.</p>
                </div>
            </article>

            <article class="product-card">
                <div class="product-art">
                    <img src="<?php echo esc_url(get_theme_file_uri('assets/images/tra-thanh-pham.jpg')); ?>" alt="Văn hóa thưởng trà" loading="lazy">
                </div>
                <div class="product-info">
                    <span class="product-tag">VĂN HÓA TRÀ</span>
                    <h3>Nghệ thuật thưởng trà</h3>
                    <p>Tìm hiểu cách pha trà và nét đẹp trong văn hóa thưởng trà Việt.</p>
                </div>
            </article>
        </div>
    </section>

    <section class="tea-quote">
        <div>
            <span class="eyebrow">HƯƠNG VỊ QUÊ HƯƠNG</span>
            <h2>Một chén trà xanh,<br>một nét đẹp văn hóa Việt.</h2>
            <a class="button-light" href="#lien-he">Tìm hiểu thêm</a>
        </div>
    </section>
</main>

<?php get_footer(); ?>
