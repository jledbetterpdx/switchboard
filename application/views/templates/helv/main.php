<?  $this->load->vars($vars); ?>
<?  foreach ($views as $view): ?>
            <section id="panel-<?=$view ?>">
<?      $this->load->view('templates/' . GLOBAL_TEMPLATE . '/panels/' . $view); ?>
            </section>
<?  endforeach; ?>