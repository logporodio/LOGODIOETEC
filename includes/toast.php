<?php
/**
 * Componente Toast - CineKids
 * Notificações flutuantes amigáveis e responsivas
 */
$toastAtual = obter_toast();
?>
<!-- Container Flutuante para Toasts -->
<div id="cinekids-toast-container" class="fixed top-5 right-5 z-50 flex flex-col gap-3 max-w-sm w-full pointer-events-none px-4 sm:px-0"></div>

<?php if ($toastAtual): ?>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        mostrarToast(<?= json_encode($toastAtual['mensagem']) ?>, <?= json_encode($toastAtual['tipo']) ?>);
    });
</script>
<?php endif; ?>
