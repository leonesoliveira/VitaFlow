function tdatagrid_inlineedit( querystring )
{
    $(function() {
        $(".inlineediting").editInPlace({
            	callback: function(unused, enteredText)
            	{
            	    __adianti_load_page( $(this).attr("action") + querystring + '&key='+ $(this).attr("key")+'&'+$(this).attr("pkey")+ '='+$(this).attr("key")+"&field="+ $(this).attr("field")+"&value="+encodeURIComponent(enteredText));
            	    return enteredText;
            	},
            	show_buttons: false,
            	text_size:20,
            	params:column=name
        });
    });
}

function tdatagrid_add_serialized_row(datagrid, row)
{
    $('#'+datagrid+' > tbody:last-child').append(row);
}

function tdatagrid_enable_groups()
{
    $('[id^=tdatagrid_] tr[level]').not('[x=1]')
        .css("cursor","pointer")
        .attr("x","1")
        .click(function(){
            if (!$(this).data('child-visible')) {
                $(this).data('child-visible', false);
            }
            $(this).data('child-visible', !$(this).data('child-visible'));
            if ($(this).data('child-visible')) {
                    $(this).siblings('[childof="'+$(this).attr('level')+'"]').hide('fast');
                }
                else {
                    $(this).siblings('[childof="'+$(this).attr('level')+'"]').show('fast');
                }
        });
}

function tdatagrid_update_total(datagrid_id)
{
	var datagrid_name = datagrid_id.substring(1);
	var target = document.querySelector( datagrid_id );
	var form = $(datagrid_id).closest('form');
    
	var observer = new MutationObserver(function(mutations) {
		mutations.forEach(function(mutation) {
		
			if (mutation.target.tagName == 'TBODY') {
				var sumFields = $(datagrid_id).find('[data-total-function=sum]');
				var countFields = $(datagrid_id).find('[data-total-function=count]');
				
				sumFields.each(function(k,v) {
					var total = 0;
					
					var column_name = $(v).data("column-name");
					var total_mask  = $(v).data("total-mask");
					var parts = total_mask.split(':');
					if (parts.length>0)
					{
						var prefix = parts[0];
						var nmask  = parts[1];
					}
					else
					{
						var prefix = '';
						var nmask  = total_mask.substring(1);
					}
					
					$('[name="'+datagrid_name+'_'+column_name+'[]"]').each(function(k,v) {
						total += parseFloat( $(v).val() );
					});

					const totalMask = number_format(total, nmask.substring(0,1), nmask.substring(1,2), nmask.substring(2,3) );
					
					if(total)
					{
						$(v).html( prefix + ' ' +  totalMask );
						$(v).data('value', total);
						$(v).attr('data-value', total);
					}

					if ($(v).data('total-form-field')) {
						const totalFormField = $(v).data('total-form-field');

						form.find('[name="'+totalFormField+'"]').val(totalMask);

					}
				});

				countFields.each(function(k,v) {
					
					var column_name = $(v).data("column-name");
					var count = $('[name="'+datagrid_name+'_'+column_name+'[]"]').length;
					var total_mask  = $(v).data("total-mask");
					
					var parts = total_mask.split(':');
					if (parts.length>0)
					{
						var prefix = parts[0];
						var nmask  = parts[1];
					}
					else
					{
						var prefix = '';
						var nmask  = total_mask.substring(1);
					}
					
					$('[name="'+datagrid_name+'_'+column_name+'[]"]').each(function(k,v) {
						total += parseFloat( $(v).val() );
					});

					const totalMask = number_format(total, nmask.substring(0,1), nmask.substring(1,2), nmask.substring(2,3) );
					
					if(total)
					{
						$(v).html( prefix + ' ' +  totalMask );
						$(v).data('value', total);
						$(v).attr('data-value', total);
					}


					if(count)
					{
						$(v).html( count );
						$(v).data('value', count);
						$(v).attr('data-value', count);
					}

					if ($(v).data('total-form-field')) {
						const totalFormField = $(v).data('total-form-field');

						$('[name="'+totalFormField+'"]').val(count)
					}
				});
			}
		});
	});
	 
	// configuração do observador:
	var config = { attributes: true, childList: true, characterData: true, subtree: true, attributeOldValue: true, characterDataOldValue: true };
	 
	// passar o nó alvo, bem como as opções de observação
	observer.observe(target, config);	
}


function tdatagrid_mutation_action(datagrid_id, mutation_url)
{
	var datagrid_name = datagrid_id.substring(1);
	var target = document.querySelector( datagrid_id );
	
	var observer = new MutationObserver(function(mutations) {
		mutations.forEach(function(mutation) {
		
		var results = {};
		var final_results = {};
		
		if (mutation.target.tagName == 'TBODY') {
			var hidden_fields = $(datagrid_id).find('[data-hidden-field=true]');
			
			hidden_fields.each(function(k,v) {
				var column_name = $(v).attr('name').replace(datagrid_name+'_', '');
				var column_name = column_name.replace('[]', '');
				
				if (column_name.substring(0,1) !== '=') {
    				if (typeof results[column_name] == 'undefined')
    				{
    				    results[column_name] = [];
    				}
    				results[column_name].push( $(v).val() );
    	        }
			});
			
    		for (prop in results)
    		{
    		    for (var i = 0; i < results[prop].length; i++) {
    		        if (typeof final_results[i] == 'undefined')
    		        {
    		            final_results[i] = {};
    		        }
    		        final_results[i][prop] = results[prop][i]
    		    }
    
    		}
    		var post_data = {};
    		
    		var parent_form = $(datagrid_id).closest('form');
    		
    		if (parent_form) {
                var form_data = $(parent_form).serializeArray();
                
                $(form_data ).each(function(index, obj){
                    var column_name = obj.name.replace('[]', '');
                    post_data[column_name] = obj.value;
                });
    		}
    		
    		post_data['list_data'] = final_results;
    		__adianti_post_exec(mutation_url, post_data, null, '1', true);
         }
	  });
	});
	 
	// configuração do observador:
	var config = { attributes: true, childList: true, characterData: true, subtree: true, attributeOldValue: true, characterDataOldValue: true };
	 
	// passar o nó alvo, bem como as opções de observação
	observer.observe(target, config);	
}


/**
 * TDataGrid Properties Popover usando Bootstrap
 */

// Função que será chamada diretamente pelo onclick do botão
function tdatagrid_show_properties(button, action) {
    // Identifica a datagrid associada ao botão
    const buttonEl = $(button);
    const datagrid = buttonEl.closest('table');
    const datagridId = datagrid.attr('id');
    console.log(datagrid);
    // Fecha qualquer outro popover aberto
    // $('.popover').popover('hide');
    
    // Se o botão já tem popover, apenas alterna sua visibilidade
    if (buttonEl.data('bs.popover')) {
        buttonEl.popover('toggle');
        return;
    }
    
    // Obtém informações das colunas
    const columns = [];
    datagrid.find('th.tdatagrid_col').each(function() {
        const columnHeader = $(this);
        const columnName = columnHeader.text().replace(/[\n\r]+|[\s]{2,}/g, ' ').trim();
        const columnIndex = columnHeader.index();
        
        // Verifica se a coluna está visível (sem display:none)
        const isVisible = !columnHeader.is(':hidden');
        
        if(columnName)
        {
            columns.push({
                value: columnHeader.attr('data-column-id'),
                name: columnName,
                index: columnIndex,
                visible: isVisible
            });
        }
    });
    
    // Valores padrão para registros por página
    const pageSizeOptions = [10, 20, 50, 100, 200, 500];
    
    // Cria o conteúdo do popover
    const popoverContent = `
        <div class="property-section">
            <label for="page-size-${datagridId}">Registros por página:</label>
            <select id="page-size-${datagridId}" class="page-size-select form-control">
                ${pageSizeOptions.map(size => `<option value="${size}">${size}</option>`).join('')}
            </select>
        </div>
                
        <div class="property-divider"></div>
        
        <div class="property-section">
            <label>${Builder.translate('visible_columns')}</label>
            <div class="columns-container">
                ${columns.map(col => `
                    <div class="column-item">
                        <label style="cursor:pointer;">
                            <input type="checkbox" 
                                  class="column-visibility" 
                                  value="${col.value}"
                                  data-column="${col.index}" 
                                  ${col.visible ? 'checked' : ''}>
                            ${col.name}
                        </label>
                    </div>
                `).join('')}
            </div>
        </div>
        
        <div class="property-section actions" style="padding-top: 15px;">
            <button class="apply-properties btn btn-sm btn-primary">${Builder.translate('apply')}</button>
            <button class="cancel-properties btn btn-sm btn-default">${Builder.translate('cancel')}</button>
        </div>

		<style>
			.tdatagrid-property-popover {
			    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1), 0 2px 8px rgba(0, 0, 0, 0.06);
				border-radius: 12px;
				border: 1px solid rgba(255, 255, 255, 0.3);	
			}
		</style>
    `;
    
    // Inicializa o popover Bootstrap
    buttonEl.popover({
        html: true,
        content: popoverContent,
        placement: 'left', // Coloca o popover à esquerda do botão
        container: 'body',
        delay: { show: 10, hide: 10 },
        trigger: 'manual',
        sanitizeFn : function(d) { return d },
        template: '<div class="popover tdatagrid-property-popover" role="tooltip"><div class="arrow"></div><div class="popover-header"></div><div class="popover-body"></div></div>'
        
    });
    
    // Quando o popover é mostrado, adiciona os eventos aos elementos
    buttonEl.on('shown.bs.popover', function() {

        const popover = $('.tdatagrid-property-popover');
        
        // Botão Cancelar
        popover.find('.cancel-properties').click(function() {
            buttonEl.popover('hide');
        });
        
        // Botão Aplicar
        popover.find('.apply-properties').click(function() {
           
            // Aplicar visibilidade das colunas
            popover.find('.column-visibility').each(function() {
                const checkbox = $(this);
                const columnIndex = checkbox.data('column');
                const isVisible = checkbox.prop('checked');
                
                tdatagrid_toggle_column_visibility(datagrid, columnIndex, isVisible);
            });

             // Obter valores selecionados
             const pageLimit = popover.find('.page-size-select').val();
             const groupColumn = popover.find('.group-column-select').val();
             
            // Salvar preferências
            tdatagrid_save_preferences(datagridId, {
                page_limit: pageLimit,
                groups: groupColumn,
                columns: getColumnsVisibility(popover)
            });            

            // Fechar o popover
            buttonEl.popover('hide');

            __adianti_post_exec(action, {
                page_limit: pageLimit,
                groups: groupColumn,
                columns: getColumnsVisibility(popover)
            }, null, true, true);

        });
    });
    
    // Quando o popover é fechado, remove os eventos para evitar duplicação
    buttonEl.on('hidden.bs.popover', function() {
        buttonEl.popover('dispose')
    });
    // Mostra o popover
    setTimeout(() => buttonEl.popover('show'),10);
}

/**
 * Obtém a configuração de visibilidade das colunas
 */
function getColumnsVisibility(popover) {
    const columnVisibility = [];
    
    popover.find('.column-visibility').each(function() {
        const checkbox = $(this);
        columnVisibility.push({
            columnId: checkbox.prop('value'),
            visible: checkbox.prop('checked')
        });
    });
    
    return columnVisibility;
}

/**
 * Alterna a visibilidade de uma coluna
 */
function tdatagrid_toggle_column_visibility(datagrid, columnIndex, isVisible) {
    // Obtém todas as células desta coluna, incluindo o cabeçalho
    const columnCells = datagrid.find(`tr > *:nth-child(${columnIndex + 1})`);
    
    if (isVisible) {
        columnCells.show();
    } else {
        columnCells.hide();
    }
}

function tdatagrid_start_hide_columns(id) {
    // Obtém todos os cabeçalhos (th) com data-start-hide='true'
    const headerCells = $(`${id} tr th[data-start-hide='true']`);
    
    headerCells.each(function(index) {
        // Obtém o índice da coluna do cabeçalho atual
        const columnIndex = $(this).index();
        
        // Esconde o cabeçalho
        $(this).hide();
        
        // Esconde todas as células (td) que estão na mesma posição de coluna
        $(`${id} tr td:nth-child(${columnIndex + 1})`).hide();
    });
}

/**
 * Salva as preferências do usuário
 */
function tdatagrid_save_preferences(datagridId, preferences) {
    // Armazena as preferências no localStorage
    if (window.localStorage) {
        localStorage.setItem(`datagrid_preferences_${datagridId}`, JSON.stringify(preferences));
    }
}
