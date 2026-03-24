import AppFooter from "@/components/layout/AppFooter";
import AppNavbar from "@/components/layout/AppNavbar";
import AffiliatePromoSection from "@/components/home/AffiliatePromoSection";
import DestinationsSection from "@/components/home/DestinationsSection";
import HeroSection from "@/components/home/HeroSection";
import HowItWorksSection from "@/components/home/HowItWorksSection";
import WhyChooseUsSection from "@/components/home/WhyChooseUsSection";

export default function HomePage() {
  return (
    <>
      <AppNavbar />
      <HeroSection />
      <HowItWorksSection />
      <DestinationsSection />
      <WhyChooseUsSection />
      <AffiliatePromoSection />
      <AppFooter />
    </>
  );
}
