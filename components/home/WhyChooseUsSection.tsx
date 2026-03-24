import { Box, Container, Heading, SimpleGrid, Text } from "@chakra-ui/react";

const features = [
  "Precios claros",
  "Conductores verificados",
  "Confirmación rápida",
  "Atención por WhatsApp",
];

export default function WhyChooseUsSection() {
  return (
    <Box py={20}>
      <Container maxW="7xl">
        <Heading mb={8}>¿Por qué elegirnos?</Heading>

        <SimpleGrid columns={{ base: 1, md: 2, lg: 4 }} gap={6}>
          {features.map((feature) => (
            <Box key={feature} p={6} rounded="2xl" borderWidth="1px">
              <Text fontWeight="semibold">{feature}</Text>
            </Box>
          ))}
        </SimpleGrid>
      </Container>
    </Box>
  );
}
