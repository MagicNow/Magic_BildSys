/**
 * Traz o insumo do servidor pelo id
 *
 * @param {Number} id
 *
 * @return jQuery.promise
 */
function getInsumo(id) {
  return $.get('/insumos/' + id + '/json');
}
