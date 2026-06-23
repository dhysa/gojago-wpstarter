module.exports = {
  content: [
    './*.php',
    './inc/**/*.php',
    './patterns/**/*.php',
    './parts/**/*.html',
    './templates/**/*.html',
    './src/**/*.js'
  ],
  theme: {
    extend: {
      colors: {
        accent: '#0f766e',
        ink: '#111827',
        muted: '#5b6472',
        line: '#d9dee7',
        canvas: '#f7f8fa'
      }
    }
  },
  corePlugins: {
    preflight: false
  }
};
