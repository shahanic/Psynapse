<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Psynapse Admin</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  :root {
    --white: #ffffff; --off: #faf9fb;
    --purple-100: #f3efff; --purple-200: #e4d9ff;
    --purple-400: #b89cfa; --purple-600: #7c4dff;
    --purple-800: #4a1fa8; --text: #1a1523;
    --muted: #8b7fa8; --border: rgba(124,77,255,0.08);
    --shadow: 0 1px 3px rgba(74,31,168,0.06), 0 8px 32px rgba(74,31,168,0.06);
  }
  body { font-family: 'DM Sans', sans-serif; background: var(--off); color: var(--text); min-height: 100vh; -webkit-font-smoothing: antialiased; }
  nav { background: rgba(255,255,255,0.85); backdrop-filter: blur(20px); border-bottom: 1px solid var(--border); padding: 0 48px; height: 64px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 10; }
  .nav-brand { display: flex; align-items: center; gap: 10px; }
  .nav-logo { width: 28px; height: 28px; background: var(--purple-600); border-radius: 8px; display: flex; align-items: center; justify-content: center; }
  .nav-logo span { color: white; font-size: 13px; font-weight: 500; }
  .brand-name { font-family: 'DM Serif Display', serif; font-size: 18px; color: var(--text); }
  .nav-right { display: flex; align-items: center; gap: 20px; }
  .nav-user { font-size: 13px; color: var(--muted); }
  .btn-logout { font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 500; color: var(--muted); background: none; border: 1px solid var(--border); padding: 7px 16px; border-radius: 100px; cursor: pointer; transition: all 0.2s ease; }
  .btn-logout:hover { color: var(--text); border-color: var(--purple-400); background: var(--purple-100); }
  main { max-width: 1100px; margin: 0 auto; padding: 56px 48px; }
  .page-header { margin-bottom: 52px; animation: fadeUp 0.6s ease both; }
  .page-eyebrow { font-size: 11px; font-weight: 500; letter-spacing: 2px; text-transform: uppercase; color: var(--purple-600); margin-bottom: 10px; }
  .page-title { font-family: 'DM Serif Display', serif; font-size: 40px; letter-spacing: -1.2px; line-height: 1.1; }
  .page-title em { font-style: italic; color: var(--purple-600); }
  .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 40px; }
  .stat-card { background: var(--white); border: 1px solid var(--border); border-radius: 20px; padding: 28px 32px; box-shadow: var(--shadow); animation: fadeUp 0.6s ease both; transition: transform 0.2s ease, box-shadow 0.2s ease; }
  .stat-card:nth-child(1){animation-delay:.1s} .stat-card:nth-child(2){animation-delay:.15s} .stat-card:nth-child(3){animation-delay:.2s}
  .stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 6px rgba(74,31,168,0.06), 0 16px 48px rgba(74,31,168,0.1); }
  .stat-label { font-size: 11px; font-weight: 500; letter-spacing: 1.5px; text-transform: uppercase; color: var(--muted); margin-bottom: 14px; }
  .stat-value { font-family: 'DM Serif Display', serif; font-size: 48px; letter-spacing: -2px; line-height: 1; }
  .stat-card.accent .stat-value { color: var(--purple-600); }
  .stat-pill { display: inline-flex; margin-top: 12px; font-size: 11px; font-weight: 500; color: var(--purple-600); background: var(--purple-100); padding: 4px 10px; border-radius: 100px; }
  .table-card { background: var(--white); border: 1px solid var(--border); border-radius: 24px; box-shadow: var(--shadow); overflow: hidden; animation: fadeUp 0.6s ease 0.25s both; }
  .table-header { padding: 28px 36px 20px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border); }
  .table-title { font-family: 'DM Serif Display', serif; font-size: 20px; letter-spacing: -0.4px; }
  .table-count { font-size: 12px; color: var(--muted); background: var(--purple-100); padding: 4px 12px; border-radius: 100px; font-weight: 500; }
  table { width: 100%; border-collapse: collapse; }
  thead th { padding: 14px 36px; text-align: left; font-size: 11px; font-weight: 500; letter-spacing: 1.5px; text-transform: uppercase; color: var(--muted); background: var(--off); }
  tbody tr { border-top: 1px solid var(--border); transition: background 0.15s ease; }
  tbody tr:hover { background: var(--purple-100); }
  tbody td { padding: 16px 36px; font-size: 14px; }
  .avatar { width: 32px; height: 32px; border-radius: 50%; background: var(--purple-200); display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 500; color: var(--purple-800); flex-shrink: 0; }
  .user-cell { display: flex; align-items: center; gap: 12px; }
  .role-badge { display: inline-flex; padding: 3px 12px; border-radius: 100px; font-size: 11px; font-weight: 500; }
  .role-admin { background: var(--purple-200); color: var(--purple-800); }
  .role-user { background: #f0f0f0; color: #666; }
  .date-text { color: var(--muted); font-size: 13px; }
  @keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
</style>
</head>
<body>

<nav>
  <div class="nav-brand">
    <div class="nav-logo"><span>Ps</span></div>
    <span class="brand-name">Psynapse</span>
  </div>
  <div class="nav-right">
    <span class="nav-user">{{ auth()->user()->name }}</span>
    <form method="POST" action="/logout">
      @csrf
      <button class="btn-logout">Sign out</button>
    </form>
  </div>
</nav>

<main>
  <div class="page-header">
    <p class="page-eyebrow">Admin Console</p>
    <h1 class="page-title">Good morning,<br><em>let's review.</em></h1>
  </div>

  <div class="stats">
    <div class="stat-card accent">
      <p class="stat-label">Total Users</p>
      <p class="stat-value">{{ $totalUsers }}</p>
      <span class="stat-pill">↑ Active</span>
    </div>
    <div class="stat-card">
      <p class="stat-label">Exams Uploaded</p>
      <p class="stat-value">0</p>
    </div>
    <div class="stat-card">
      <p class="stat-label">Questions Generated</p>
      <p class="stat-value">0</p>
    </div>
  </div>

  <div class="table-card">
    <div class="table-header">
      <h2 class="table-title">All Users</h2>
      <span class="table-count">{{ $totalUsers }} total</span>
    </div>
    <table>
      <thead>
        <tr>
          <th>User</th>
          <th>Email</th>
          <th>Role</th>
          <th>Joined</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)
        <tr>
          <td>
            <div class="user-cell">
              <div class="avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
              {{ $user->name }}
            </div>
          </td>
          <td>{{ $user->email }}</td>
          <td><span class="role-badge {{ $user->role === 'admin' ? 'role-admin' : 'role-user' }}">{{ $user->role }}</span></td>
          <td class="date-text">{{ $user->created_at->format('M d, Y') }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</main>

</body>
</html>