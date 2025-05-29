        </main>
        <footer class="site-footer-bottom">
            <div class="container">
                <p>© <?php echo date("Y"); ?> HotelSys. Todos los derechos reservados.</p>
                <p>Proyecto para Pruebas de Software. Hecho con ❤️</p>
            </div>
        </footer>
    </div> <!-- .site-wrapper -->

    <script src="<?php echo $base_url; ?>js/main.js"></script>
    <?php if (isset($specific_js) && is_array($specific_js)): ?>
        <?php foreach ($specific_js as $js_file): ?>
            <script src="<?php echo $base_url; ?>js/<?php echo htmlspecialchars($js_file); ?>"></script>
        <?php endforeach; ?>
    <?php elseif (isset($specific_js)): ?>
        <script src="<?php echo $base_url; ?>js/<?php echo htmlspecialchars($specific_js); ?>"></script>
    <?php endif; ?>
</body>
</html>
<?php
// Cerrar conexión a la base de datos si $conn existe y es una instancia de mysqli
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>