<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link <?php echo (uri_string() == '') ? "" : "collapsed" ?>" href="/">
                <i class="bi bi-grid"></i>
                <span>Home</span>
            </a>
        </li><li class="nav-item">
            <a class="nav-link <?php echo (uri_string() == 'keranjang') ? "" : "collapsed" ?>" href="keranjang">
                <i class="bi bi-cart-check"></i>
                <span>Keranjang</span>
            </a>
        </li><?php
          if (session()->get('role') == 'admin') {
          ?>
              <li class="nav-item">
                  <a class="nav-link <?php echo (uri_string() == 'produk') ? "" : "collapsed" ?>" href="produk">
                      <i class="bi bi-receipt"></i>
                      <span>Produk</span>
                  </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php echo (uri_string() == 'diskon') ? "" : "collapsed" ?>" href="<?= base_url('diskon') ?>">
                    <i class="bi bi-tags-fill"></i>
                    <span>Diskon</span>
                </a>
              </li>
              
              <li class="nav-item">
                  <a class="nav-link <?php echo (uri_string() == 'pembelian') ? "" : "collapsed" ?>" href="<?= base_url('pembelian') ?>">
                      <i class="bi bi-shop"></i>
                      <span>Pembelian</span>
                  </a>
              </li>
              <?php
          }
          ?>

        <li class="nav-item">
            <a class="nav-link <?php echo (uri_string() == 'history') ? "" : "collapsed" ?>" href="history">
                <i class="bi bi-person"></i>
                <span>History</span>
            </a>
        </li><li class="nav-item">
            <a class="nav-link <?php echo (uri_string() == 'contact') ? "" : "collapsed" ?>" href="contact">
            <i class="bi bi-file-person-fill"></i>
            <span>Contact</span>
            </a>
        </li>
        
    </ul>

</aside>```
