class PeriodicalUpdater {
    constructor(container, url, options = {}) {
      this.container = document.querySelector(container);
      this.url = url;
      this.options = options;
  
      this.frequency = this.options.frequency || 2000; // Padrão: 2 segundos (em ms)
      this.decay = this.options.decay || 1;
  
      this.lastText = null;
      this.timer = null;
      this.running = false;
  
      this.onComplete = this.options.onComplete || function () {};
      this.onSuccess = this.options.onSuccess || function () {};
      this.onFailure = this.options.onFailure || function () {};
  
      this.start();
    }
  
    async fetchUpdate() {
      try {
        const response = await fetch(this.url, {
          method: this.options.method || 'GET',
          headers: this.options.headers || { 'Content-Type': 'application/json' },
          body: this.options.method === 'POST' ? JSON.stringify(this.options.parameters || {}) : null,
        });
  
        if (!response.ok) {
          this.onFailure(response);
          throw new Error(`HTTP error! Status: ${response.status}`);
        }
  
        const text = await response.text();
  
        // Atualiza o container, se especificado
        if (this.container) {
          this.container.innerHTML = text;
        }
  
        this.onSuccess(text, response);
  
        // Decay logic
        if (this.options.decay) {
          this.decay = text === this.lastText ? this.decay * this.options.decay : 1;
          this.lastText = text;
        }
  
        // Chama onComplete
        this.onComplete(text, response);
      } catch (error) {
        console.error('Error fetching update:', error);
      }
    }
  
    start() {
      if (this.running) return;
  
      this.running = true;
      const update = async () => {
        await this.fetchUpdate();
  
        if (this.running) {
          this.timer = setTimeout(update, this.frequency * this.decay);
        }
      };
  
      update();
    }
  
    stop() {
      this.running = false;
      clearTimeout(this.timer);
      this.onComplete();
    }
  }
  