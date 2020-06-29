<template>
  <div class="container">
    <form class="flex flex-col h-screen items-center justify-center">
      <div class="grid grid-cols-3 col-gap-8 row-gap-12 w-full">
        <input
          type="text"
          pattern="[0-9]*"
          class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl text-center p-2 border border-grey-400 focus:border-orange-500 rounded-sm appearance-none shadow-none focus:outline-none focus:shadow-outline"
          v-model.number="duration.hours"
          ref="durationHours"
          @focus="duration.hours = ''"
          @blur="duration.hours = duration.hours === '' ? 0 : duration.hours">
        <input
          type="text"
          pattern="[0-9]*"
          class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl text-center p-2 border border-grey-400 focus:border-orange-500 rounded-sm appearance-none shadow-none focus:outline-none focus:shadow-outline"
          v-model.number="duration.minutes"
          ref="durationMinutes"
          @focus="duration.minutes = ''"
          @blur="duration.minutes = duration.minutes === '' ? 0 : duration.minutes">
        <input
          type="text"
          pattern="[0-9]*"
          class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl text-center p-2 border border-grey-400 focus:border-orange-500 rounded-sm appearance-none shadow-none focus:outline-none focus:shadow-outline"
          v-model.number="duration.seconds"
          ref="durationSeconds"
          @focus="duration.seconds = ''"
          @blur="duration.seconds = duration.seconds === '' ? 0 : duration.seconds">
        <NotificationIcon :allowNotifcations="allowNotifcations"/>
        <button
          type="submit"
          class="col-span-2 w-full h-12 md:h-16 ml-auto px-4 bg-orange-500 hover:bg-orange-600 rounded-sm md:text-lg text-white font-700 uppercase leading-none tracking-wide focus:outline-none focus:shadow-outline select-none transition-all duration-100"
          :class="{ 'cursor-not-allowed': disabled }"
          :disabled="disabled"
          @click.prevent="start">
          {{ ! timerRunning ? 'Start' : 'Stop' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script>
  import NotificationIcon from './NotificationIcon';
  import moment from 'moment';
  export default {
    components: {
      NotificationIcon,
    },

    data() {
      return {
        timerRunning: false,
        timerId: '',
        timeRemaining: '',
        timerEnd: '',
        duration: this.defaultDuration(),
        allowNotifcations: "Notification" in window && Notification.permission === 'granted' ? true : false,
      }
    },

    computed: {
      disabled: function () {
        return ! this.duration.hours && ! this.duration.minutes && ! this.duration.seconds;
      },
    },

    methods: {
      start() {
        if (this.timerRunning) {
          this.stop();
          return;
        }

        if ( ! ("Notification" in window)) {
          alert("This browser does not support desktop notifications. You will not be notified when the timer ends.")
          this.startTimer();
        } else {
          if (this.checkNotificationPromise()) {
            Notification.requestPermission().then(permission => this.handlePermission(permission));
          } else {
            Notification.requestPermission(permission => this.handlePermission(permission));
          }
        }
      },

      startTimer() {
        this.timerRunning = true;
        this.blurInputs();
        this.setTimer();
      },

      checkNotificationPromise() {
        try {
          Notification.requestPermission().then();
        } catch(e) {
          return false;
        }
          return true;
      },

      handlePermission(permission) {
        if (permission === 'granted') {
          this.allowNotifcations = true;
        } else if (permission === 'denied') {
          alert('Desktop notifications are blocked. Please enable them in your browser to be notified when the timer ends.');
        } else if (permission === 'default') {
          alert('Turn on desktop notifications to be notified when the timer ends.');
        }

        this.startTimer();
      },

      setTimer() {
        this.timerEnd = moment.utc().add({
          hours: this.duration.hours,
          minutes: this.duration.minutes,
          seconds: this.duration.seconds,
        });
        this.timer();
        this.setDocumentTitle();
        this.timerId = setInterval(() => {
          this.timer();
        }, 1000);
      },

      timer() {
        this.timeRemaining = moment.utc(this.timerEnd.diff(moment.utc()));
        this.duration.hours = this.timeRemaining.hours();
        this.duration.minutes = this.timeRemaining.minutes();
        this.duration.seconds = this.timeRemaining.seconds();

        this.setDocumentTitle();

        ! this.isFinished(this.timeRemaining.format('HH:mm:ss')) || this.finish();
      },

      isFinished(timeRemaining) {
        return timeRemaining === '00:00:00';
      },

      finish() {
        this.stop();
        const notification = new Notification('Task Finished');
      },

      stop() {
        this.timerRunning = false;
        clearInterval(this.timerId);
      },

      reset() {
        this.duration = this.defaultDuration();
      },

      defaultDuration() {
        return {
          hours: 0,
          minutes: 0,
          seconds: 0,
        }
      },

      setDocumentTitle() {
        document.title = `${this.timeRemaining.format('HH:mm:ss')} | ${window.countdownTimer.appName}`;
      },

      blurInputs() {
        this.$refs.durationHours.blur();
        this.$refs.durationMinutes.blur();
        this.$refs.durationSeconds.blur();
      },
    }
  }
</script>
