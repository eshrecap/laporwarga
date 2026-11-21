document.addEventListener('DOMContentLoaded', () => {
  const readData = (canvas) => {
    try {
      return {
        labels: JSON.parse(canvas.dataset.labels || '[]'),
        values: JSON.parse(canvas.dataset.values || '[]')
      };
    } catch (_) {
      return { labels: [], values: [] };
    }
  };

  if (window.Chart) {
    const make = (id, type) => {
      const canvas = document.getElementById(id);
      if (!canvas) return;
      const { labels, values } = readData(canvas);
      new Chart(canvas, {
        type,
        data: { labels, datasets: [{ label: 'Jumlah laporan', data: values, borderWidth: 2 }] },
        options: { responsive: true, plugins: { legend: { display: type === 'doughnut' } } }
      });
    };
    make('monthChart', 'bar');
    make('categoryChart', 'doughnut');
    return;
  }

  // Canvas fallback keeps the dashboard charts usable when the computer is offline.
  const drawBars = (canvas) => {
    const { labels, values } = readData(canvas);
    const ctx = canvas.getContext('2d');
    const width = canvas.clientWidth || 480;
    const height = 260;
    canvas.width = width * (window.devicePixelRatio || 1);
    canvas.height = height * (window.devicePixelRatio || 1);
    ctx.scale(window.devicePixelRatio || 1, window.devicePixelRatio || 1);
    ctx.clearRect(0, 0, width, height);
    if (!labels.length) {
      ctx.fillText('Belum ada data untuk ditampilkan.', 12, 28);
      return;
    }
    const max = Math.max(1, ...values);
    const left = 36, bottom = 35, top = 15, plotH = height - top - bottom;
    ctx.strokeStyle = '#b8c3d1'; ctx.beginPath(); ctx.moveTo(left, top); ctx.lineTo(left, height - bottom); ctx.lineTo(width - 8, height - bottom); ctx.stroke();
    const slot = (width - left - 12) / labels.length;
    values.forEach((value, i) => {
      const barH = (Math.max(0, value) / max) * (plotH - 12);
      const x = left + i * slot + slot * .18;
      const barW = Math.max(3, slot * .62);
      ctx.fillStyle = '#2878bd'; ctx.fillRect(x, height - bottom - barH, barW, barH);
      ctx.fillStyle = getComputedStyle(document.body).color; ctx.font = '11px sans-serif';
      ctx.fillText(String(value), x, height - bottom - barH - 4);
      const label = String(labels[i]);
      ctx.save(); ctx.translate(x + barW / 2, height - bottom + 9); ctx.rotate(-.35); ctx.fillText(label.slice(0, 14), 0, 0); ctx.restore();
    });
  };
  const drawDonut = (canvas) => {
    const { labels, values } = readData(canvas);
    const ctx = canvas.getContext('2d');
    const width = canvas.clientWidth || 480, height = 280, dpr = window.devicePixelRatio || 1;
    canvas.width = width * dpr; canvas.height = height * dpr; ctx.scale(dpr, dpr); ctx.clearRect(0, 0, width, height);
    const total = values.reduce((a, b) => a + Math.max(0, b), 0);
    if (!total) { ctx.fillStyle = getComputedStyle(document.body).color; ctx.fillText('Belum ada data untuk ditampilkan.', 12, 28); return; }
    const colors = ['#2878bd', '#16835d', '#e6a23c', '#a15bc2', '#d95757', '#5a9fa8', '#78869a'];
    const cx = Math.min(width * .35, 150), cy = 132, radius = 86; let angle = -Math.PI / 2;
    values.forEach((value, i) => { const slice = Math.max(0, value) / total * Math.PI * 2; ctx.beginPath(); ctx.moveTo(cx, cy); ctx.arc(cx, cy, radius, angle, angle + slice); ctx.closePath(); ctx.fillStyle = colors[i % colors.length]; ctx.fill(); angle += slice; });
    ctx.beginPath(); ctx.arc(cx, cy, 48, 0, Math.PI * 2); ctx.fillStyle = getComputedStyle(document.body).backgroundColor; ctx.fill();
    ctx.fillStyle = getComputedStyle(document.body).color; ctx.font = 'bold 17px sans-serif'; ctx.textAlign = 'center'; ctx.fillText(String(total), cx, cy + 6); ctx.textAlign = 'left';
    const lx = Math.min(width * .62, 265); let ly = 40;
    labels.forEach((label, i) => { ctx.fillStyle = colors[i % colors.length]; ctx.fillRect(lx, ly - 10, 10, 10); ctx.fillStyle = getComputedStyle(document.body).color; ctx.font = '12px sans-serif'; ctx.fillText(`${String(label).slice(0, 18)} (${values[i]})`, lx + 16, ly); ly += 25; });
  };
  drawBars(document.getElementById('monthChart'));
  drawDonut(document.getElementById('categoryChart'));
});
