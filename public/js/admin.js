/**
 * Maneja la confirmación de eliminación con SweetAlert2
 * @param {HTMLFormElement} form - El formulario a enviar
 * @param {number} postId - ID del post a eliminar
 */
function confirmDelete(form, postId) {
  Swal.fire({
    title: '¿Eliminar publicación?',
    text: 'Esta acción es irreversible. No podrás recuperar la publicación una vez eliminada.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc2626',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar',
    buttonsStyling: true,
  }).then((result) => {
    if (result.isConfirmed) {
      form.submit();
    }
  });

  return false;
}
