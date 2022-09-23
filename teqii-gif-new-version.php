<?php
/**
 *
 * Plugin Name: Teqii Nova versão - GIF
 * Description: Adicionar gif no produto * Author: Teqii Commerce
 * Plugin URI: https://teqii.com.br
 * Version: 2.0.13
*/
add_action('admin_menu', 'teqii_adicionar_gif_menu' );

function teqii_adicionar_gif_menu() {
    add_menu_page( 'Adicionar Gif', 
     'Adicionar Gif',
     'publish_posts',
     'teqii-adicionar-gif-plugin', 
     'adicionarGif', 
     'dashicons-buddicons-community' );
}

function adicionarGif() {
    if (isset($_POST) && count($_POST) > 0) {
        $id = wc_get_product_id_by_sku( 'Gif Customizado' );
        if ($id) {
            $product = wc_get_product( $id );
        } else {
            $product = new WC_Product();
        }
        $product->set_sku('Gif Customizado');
        $product->set_regular_price(0);   
        $product->set_sale_price(0);
        $product->set_width(0);
        $product->set_height(0);
        $product->set_weight(0);
        $product->set_catalog_visibility('hidden'); 
       
        if (
            isset($_FILES['teqii_imagem_gif']) &&
            !empty($_FILES['teqii_imagem_gif']['tmp_name']) &&
            is_uploaded_file($_FILES['teqii_imagem_gif']['tmp_name'])
            ) {            
            $attachment_id = media_handle_upload('teqii_imagem_gif', 0);
            
            if (is_wp_error($attachment_id)) {
                echo '<div class="notice notice-error">Erro ao enviar a imagem do gif</div>';
            } else {
                update_option( 'teqii_imagem_gif', $attachment_id );
                $product->set_image_id( $attachment_id );
            }
        }
        $product->save();
        update_option('teqii_habilitar_gif', $_POST['teqii_habilitar_gif']);
        update_option('teqii_valor_minimo_gif', $_POST['teqii_valor_minimo_gif']);
    }

    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing_teqii_gif_product', 50 );
    function woocommerce_template_single_sharing_teqii_gif_product() {
        $url =  get_site_url().'';
        echo "<img class='teqii-imgb' src='$url'>";
    }
    ?>
    
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <div id="universal-message-container">
            <div>
                <p>Preencha as informações a seguir para adicionar um gif no carrinho.</p>
            </div>
            <form method="post" enctype="multipart/form-data">
                <div>
                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th scope="row" class="titledesc">
                                    <label for="teqii_habilitar_gif">Habilitar o gif </label>
                                </th>
                                <td class="forminp forminp-text">
                                    <select name="teqii_habilitar_gif">
                                        <option <?= get_option( 'teqii_habilitar_gif', '' ) !== 'sim' ? 'selected="selected"' : '' ?> value="nao">Não</option>
                                        <option <?= get_option( 'teqii_habilitar_gif', '' ) === 'sim' ? 'selected="selected"' : '' ?> value="sim">Sim</option>
                                    </select>
                                    <p class="description">Ativar Gif.</p>
                                </td>
                            </tr>
                            
                            <tr valign="top">
                                <th scope="row" class="titledesc">
                                    <label for="teqii_imagem_gif">GIF ou Imagem</label>
                                </th>
                                <td class="forminp forminp-text">
                                    <input name="teqii_imagem_gif" type="file" value="" placeholder="" multiple="false">
                                    <p class="description">Gif ou Imagem adicionada na página do produto.</p>
                                    <?php                                
                                    if (get_option( 'teqii_imagem_gif', '' )) { ?>
                                        <img src="<?= wp_get_attachment_url( get_option( 'teqii_imagem_gif', '' ), '' );  ?>" />
                                    <?php } ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php
                        submit_button();
                    ?>
                </div>
            </form>
        </div>
    </div>
    <?php
}


function wpdocs_theme_name_scripts_teqii_gif_new_version() {
    $plugin_url = plugin_dir_url( __FILE__ );

    wp_enqueue_style( 'style',  $plugin_url . 'css/teqii-gif.css');
}
add_action( 'wp_enqueue_scripts', 'wpdocs_theme_name_scripts_teqii_gif_new_version' );