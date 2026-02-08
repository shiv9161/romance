import nodemailer from 'nodemailer';

export default async function handler(req, res) {
  res.setHeader('Access-Control-Allow-Origin', '*');
  res.setHeader('Access-Control-Allow-Methods', 'POST, OPTIONS');
  res.setHeader('Access-Control-Allow-Headers', 'Content-Type');

  if (req.method === 'OPTIONS') {
    return res.status(200).end();
  }

  if (req.method !== 'POST') {
    return res.status(405).json({ success: false, message: 'Method not allowed' });
  }

  const config = {
    host: process.env.SMTP_HOST || 'smtp.gmail.com',
    port: parseInt(process.env.SMTP_PORT || '587', 10),
    secure: process.env.SMTP_SECURE === 'true',
    auth: {
      user: process.env.SMTP_USER || 'samaelgaming9161@gmail.com',
      pass: process.env.SMTP_PASS || 'enlq lwcd ytbm tlyi',
    },
  };

  const fromEmail = process.env.SMTP_FROM || 'samaelgaming9161@gmail.com';
  const fromName = process.env.SMTP_FROM_NAME || 'Valentine Surprise';
  const toEmail = process.env.SMTP_TO || 'shivduttkumar9161@gmail.com';

  try {
    const transporter = nodemailer.createTransport(config);
    await transporter.sendMail({
      from: `"${fromName}" <${fromEmail}>`,
      to: toEmail,
      subject: '💖 Someone said YES to being your Valentine!',
      text: `Congratulations! 🎉\n\nSomeone clicked YES on your Valentine's page!\n\nThis is the beginning of something beautiful. Get ready for chocolates, flowers, and lots of love! 🍫🌹❤️\n\nSent at ${new Date().toISOString()}`,
    });
    res.status(200).json({ success: true, message: 'Email sent! Check your inbox.' });
  } catch (err) {
    res.status(500).json({ success: false, message: 'Failed to send: ' + err.message });
  }
}
