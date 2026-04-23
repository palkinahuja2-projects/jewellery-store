<?php
session_start();
include '../include/db.php';
$page_title = 'Messages';

$result = mysqli_query($conn, "SELECT * FROM contact_messages ORDER BY id DESC");
$total  = mysqli_num_rows($result);

include 'admin_nav.php';
?>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
  <div style="font-size:0.82rem;color:var(--muted);"><?php echo $total; ?> message<?php echo $total!=1?'s':''; ?></div>
</div>

<?php if($total > 0): ?>
  <?php while($row = mysqli_fetch_assoc($result)): ?>
    <div class="msg-card">
      <div class="msg-header">
        <div>
          <div class="msg-name"><?php echo htmlspecialchars($row['name']); ?></div>
          <div class="msg-email">📧 <?php echo htmlspecialchars($row['email']); ?></div>
        </div>
        <div class="msg-date"><?php echo htmlspecialchars($row['created_at'] ?? ''); ?></div>
      </div>
      <div class="msg-body"><?php echo nl2br(htmlspecialchars($row['message'])); ?></div>
      <div style="margin-top:0.8rem;">
        <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="btn btn-outline btn-sm">✉️ Reply</a>
      </div>
    </div>
  <?php endwhile; ?>
<?php else: ?>
  <div class="empty-state">
    <span class="empty-icon">💌</span>
    <p>No messages yet</p>
    <div style="font-size:0.82rem;margin-top:0.5rem;">Customer messages from the contact form will appear here.</div>
  </div>
<?php endif; ?>

<?php include 'admin_footer.php'; ?>