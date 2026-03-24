import { Box, Container, Heading, SimpleGrid, Text, VStack } from "@chakra-ui/react";

const items = [
  { title: "Elige ruta", text: "Selecciona origen, destino y fecha." },
  { title: "Confirma reserva", text: "Revisa precio y completa tus datos." },
  { title: "Viaja tranquilo", text: "Recibe confirmación y detalles del servicio." },
];

export default function HowItWorksSection() {
  return (
    <Box py={20}>
      <Container maxW="7xl">
        <VStack gap={10}>
          <Heading textAlign="center">¿Cómo funciona?</Heading>

          <SimpleGrid columns={{ base: 1, md: 3 }} gap={6} w="full">
            {items.map((item) => (
              <Box key={item.title} p={8} rounded="2xl" bg="gray.50" borderWidth="1px">
                <Heading size="md" mb={3}>
                  {item.title}
                </Heading>
                <Text color="gray.600">{item.text}</Text>
              </Box>
            ))}
          </SimpleGrid>
        </VStack>
      </Container>
    </Box>
  );
}
