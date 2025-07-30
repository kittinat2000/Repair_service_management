<style>
  .pagination {
    margin: 10px 0;
    text-align: center;
  }

  .pagination a,
  .pagination strong {
    display: inline-block;
    margin: 0 3px;
    padding: 5px 10px;
    text-decoration: none;
    border: 2px solid #000;
    border-radius: 4px;
    color: #000;
    background: #fff;
    font-weight: bold;
    border-radius: 8px;
    transition: background 0.3s, color 0.3s;
  }

  .pagination strong {
    background: #000;
    color: #fff;
  }

  .pagination a:hover {
    background: #000;
    color: #fff;
  }
</style>

<div class="pagination">
  <?php if ($page > 1): ?>
    <a href="?page=1">
      << </a>
      <?php endif; ?>

      <?php for ($i = max(1, $page - 1); $i <= min($totalPages, $page + 1); $i++): ?>
        <?php if ($i == $page): ?>
          <strong><?= $i ?></strong>
        <?php else: ?>
          <a href="?page=<?= $i ?>"><?= $i ?></a>
        <?php endif; ?>
      <?php endfor; ?>

      <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $totalPages ?>">>></a>
      <?php endif; ?>
</div>