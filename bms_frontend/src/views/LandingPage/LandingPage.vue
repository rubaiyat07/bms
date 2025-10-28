<template>
  <div class="landing-page">
    <HeroSection />
    <WelcomeCarousel />
    <FeaturesSection />
    <StatsSection />
    <BenefitsSection />
    <CTASection />
  </div>
</template>

<script>
import HeroSection from './components/HeroSection.vue'
import WelcomeCarousel from './components/WelcomeCarousel.vue'
import FeaturesSection from './components/FeaturesSection.vue'
import StatsSection from './components/StatsSection.vue'
import BenefitsSection from './components/BenefitsSection.vue'
import CTASection from './components/CTASection.vue'

export default {
  name: 'LandingPage',
  components: {
    HeroSection,
    WelcomeCarousel,
    FeaturesSection,
    StatsSection,
    BenefitsSection,
    CTASection
  }
}
</script>

<style scoped>
.landing-page {
  /* Add styles here */
}
</style>
